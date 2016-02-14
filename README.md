# SCRUMptious
SCRUMptious is a free, open source, flexible project management web application based on
**Orangescrum**. To the best of my ability, I will try to merge in updates from Orangescrum so that hopefully this project will just be Orangescrum + more.


## Requirements
* [A Docker host]((https://www.digitalocean.com/features/one-click-apps/docker/)).
* [Composer](http://blog.programster.org/ubuntu-install-composer/) if you are installing from the development line rather than a release.
  

## Installation / Deployment

### From A Release
  1. Download one of the releases from the [releases page](https://github.com/programster/SCRUMptious/releases).
  2. Extract the source code and navigate to within the folder.
  3. Navigate to the `docker` directory.
  4. Execute `bash build.sh`
  5. Execute `bash deploy.sh`
  6. Navigate to your host's IP or domain name in your web browser.
  7. That's it.

### From Source
  1. Clone this repository
    * `git clone git@github.com:programster/SCRUMptious.git`
  2. Navigate into the cloned repository.
    * `cd SCRUMptious`
  2. Checkout the `programster` branch (which is my main line of development for SCRUMptious)
     * `git checkout programster`
  3. Run a composer install to fetch all the external libraries.
     * `composer install`
  4. Navigate to the docker directory
     * `cd docker`
  5. Execute `bash build.sh`
  6. Execute `bash deploy.sh`
  7. Navigate to your host's IP or domain name in your web browser.
  8. That's it.


## How to Contribute
  1. Fork [the project on Github](https://github.com/programster/SCRUMptious).
  2. Clone the repository to your system/working directory
  3. Checkout the programster branch.
  4. From there start your own branch named after the functionality/fix you wish to implement.
  3. Implement your changes with as clean a series of commits as you can with commit comments.
  4. Test it works by building and running the docker container and testing your functionality.
  5. Merge your changes into the programster branch.
  5. Commit/Push the code to your repository
  6. Send a Pull request to SCRUMptious git repository


## Why Did I Fork?
I grew tired of waiting for Orangescrum to implement my merge request for docker support, and felt that the tool could also benefit from some other changes, such as migration scripts. I would love it if the Orangescrum team would pull my features or politely tell me they are not interested (and maybe why?).

## Licensing
GNU General Public License version 3. For more information refer to the LICESNSE.txt file.

## References
 * [SCRUMptious Github Page](https://github.com/programster/SCRUMptious)
 * [SCRUMptious Pull Page](https://github.com/programster/SCRUMptious/pulls)
 * [Orangescrum Official website](http://www.orangescrum.com)
 * [Orangescrum Blog](http://blog.orangescrum.com)
 * [Orangescrum Downloads](http://www.orangescrum.org/free-download)
 * [Orangescrum Issue Tracker](https://github.com/Orangescrum/orangescrum/issues)
 * [Orangescrum Google Group](https://groups.google.com/group/orangescrum-community-support)
 * [Orangescrum Youtube](https://www.youtube.com/watch?v=4qCaP0TZuxU)
 * [Programster's Blog](http://blog.programster.org)
