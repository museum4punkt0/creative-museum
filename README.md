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
```
ddev backend-install
ddev frontend-install
```
### Frontend ###
```
ddev frontend-dev
https://creative-museum.ddev.site/
```