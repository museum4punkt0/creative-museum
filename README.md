# Förderung #
Das Projekt museum4punkt0 wird gefördert durch die Beauftragte der Bundesregierung für Kultur und Medien aufgrund eines Beschlusses des Deutschen Bundestages. Weitere Informationen: www.museum4punkt0.de

The project museum4punkt0 is funded by the Federal Government Commissioner for Culture and the Media in accordance with a resolution issued by the German Bundestag (Parliament of the Federal Republic of Germany). Further information: www.museum4punkt0.de
![image](https://github.com/museum4punkt0/creative-museum/assets/129384233/0157ca7e-b812-4d3e-8ec0-d7716769fc99)


# Install instructions #

## Requirements ##
- [Git](https://git-scm.com/) - Distributed version-control system for tracking
  changes in source code during software development.
- [Git LFS](https://git-lfs.github.com/) - Git LFS (Large
  File Storage) is a Git extension, that reduces the impact of large files in
  a repository by downloading the relevant versions of them lazily.
- [Composer](https://getcomposer.org/) - Dependency manager for the PHP
  programming language.
- [DDEV](https://github.com/drud/ddev) - Open source tool that makes it simple
  to get local PHP development environments up and running in minutes.
- Docker Environment
  - [Docker](https://www.docker.com/products/docker-desktop) - Docker Desktop is a
    tool for MacOS and Windows machines for the building and sharing of
    containerized applications and microservices.
  - [Colima](https://github.com/abiosoft/colima) - Colima is a Docker Desktop alternative which brings aproximately 10 times better performance (Install HEAD for best performance ```brew unlink colima && brew install --HEAD colima```)

## Clone ##
Clone from remote repository into your local directory:
```
git clone git@bitbucket.org:jwied/creative-museum.git
```

## DDEV start ##
After cloning you have to start your *DDEV* services (usually *web* and *db*).
Navigate to your project root directory and run the following command:
```
ddev start
```

## Install dependencies ##
After the project was started successfully you will have to install all required
dependencies via *npm*. Navigate to the root directory of your project and
execute the following command:

[Backend](https://backend.creative-museum.ddev.site)
```
ddev backend-install
```

[Frontend](https://creative-museum.ddev.site)
```
ddev frontend-install
```

[TYPO3](https://typo3.creative-museum.ddev.site/typo3)
```
ddev typo3-install
ddev import-db --target-db=typo3 --src=data/typo3.sql.gz
```
### Frontend Watcher ###
```
ddev frontend-dev
```
