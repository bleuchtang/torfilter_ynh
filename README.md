# TorFilter
## Overview

TorFilter app for [YunoHost](http://yunohost.org/).

Install Tor and broadcasting it through a wifi hotspot. By default user
connected on this wifi are not able to access to Internet except by installing
a tor software on their computer (Torbrowser or Tails for instance). You can
authorized device to access to Internet through the web interface. These
devices will access to internet through the internal tor client of the internet
cube. If someone connect to the Tor wifi and is not allowed to access to
Internet and doesn't have a tor application on his computer, he will be
redirect to a captive portal when he open a web browser and go to any web site.

Schema with an iptables point of view:
   
   
   
                                                                                                        .--.               
                                                                                                    _ -(    )- _           
                                                                                               .--,(            ),--.      
                                                                                           _.-(                       )-._ 
                                                                       .----------------> (           INTERNET            )
                                                        .-----------.  |                   '-._(                     )_.-' 
                             .------------------------->| tor socks |--'                        '__,(            ),__'     
                             |                          '-----------'                                - ._(__)_. -          
                             |                               ^                                            ^
                             |                               |                          .-----------------'
                             |                               |                          |
                             |                               |                          |
                             |                               |                          |
    .------.    .------------------------.  .---------------------------------.  .---------------.    .---------.    .------.
    | wlan |--->| IP/MAC authorized src  |->| Port 443 www.torproject.org dst |->| Tor Nodes dst |--->| default |--->| DROP |
    '------'    '------------------------'  '---------------------------------'  '---------------'    '---------'    '------'
                                                                                                         |   |
                                                                                                         |   |
                                                                                                         |   |
                                                                                                         |   |
                                                                                                         |   |  .---------.     .----------.
                                                                                                         |   '->| Port 53 |---->| Fake DNS |
                                                                                                         |      '---------'     '----------'
                                                                                                         |
                                                                                                         |       .---------.    .----------------.
                                                                                                         '------>| Port 80 |--->| Captive portal |
                                                                                                                 '---------'    '----------------'
   
   
   
   
This YunoHost app is a part of the "[La Brique Internet](http://labriqueinter.net)" project but can be used independently.

## Prerequisites

* Debian Jessie
* YunoHost >= 2.2.0
* [Hotspot app for YunoHost](https://github.com/labriqueinternet/hotspot_ynh)
