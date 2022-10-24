<?php

namespace App\Command;

use App\Enum\MailType;
use App\Repository\CampaignRepository;
use App\Service\MailService;
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

    private MailService $mailService;

    public function __construct
    (
        CampaignRepository     $campaignRepository,
        EntityManagerInterface $entityManager,
        MailService $mailService,
        string                 $name = null
    )
    {
        parent::__construct($name);
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
        $this->mailService = $mailService;
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

            if (Carbon::now()->between($startDate, $endDate) && !$campaign->getActive()) {
                $campaign->setActive(true);
                $campaign->setClosed(false);
                $this->mailService->sendMail(MailType::NEW_CAMPAIGN->value,null,['campaign' => $campaign]);
            } elseif (!$campaign->getClosed()) {
                $campaign->setClosed(true);
                $this->mailService->sendMail(MailType::CAMPAIGN_CLOSED->value,null,['campaign' => $campaign]);
            }
            $this->entityManager->persist($campaign);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}