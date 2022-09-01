<?php

namespace App\Command;

use App\Repository\CampaignRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:campaign-activation',
)]
class HandleCampaignActivationCommand extends Command
{
    protected static $defaultName = 'app:campaign-activation';

    private CampaignRepository $campaignRepository;

    private EntityManagerInterface $entityManager;

    public function __construct
    (
        CampaignRepository $campaignRepository,
        EntityManagerInterface $entityManager,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setHelp('This command checks and handles the activation of campaigns.');
        $this->setDescription('Handle Campaign activations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $campaigns = $this->campaignRepository->findBy([
            'closed' => false
        ]);

        foreach ($campaigns as $campaign) {
            $startDate = Carbon::createFromInterface($campaign->getStart());
            $endDate = Carbon::createFromInterface($campaign->getStop());

            if (Carbon::now()->between($startDate,$endDate)){
                $campaign->setActive(true);
            }else{
                $campaign->setActive(false);
            }
            $this->entityManager->persist($campaign);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}