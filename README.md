# TorFilter
## Overview

TorFilter app for [YunoHost](http://yunohost.org/).

Install Tor and broadcasting it through a wifi hotspot. By default user
connected on this wifi are not able to access to Internet except by installing
a tor software on their computer (Torbrowser or Tails for instance). You can
authorized device to access to Internet through the web interface. These
devices will access to internet through the internal tor client of the internet
cube. If someone was connected to the Tor wifi, he doesn't have a tor
application on his computer, and is not allowed to access to Internet, he will
be redirect to a captive portal.

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
     .------.  .------------------------.  .---------------------------------.  .---------------.  .-------------.  .-------------.  .------.
     | wlan |->| IP/MAC authorized src  |->|  Port 443 *.torproject.org dst  |->| Tor Nodes dst |->| Port 53 dst |->| Port 80 dst |->| DROP |
     '------'  '------------------------'  |  Port 443 *.tails.boum.org dst  |  '---------------'  '-------------'  '-------------'  '------'
                                           '---------------------------------'                            |                |
                                                                                                          |                |
                                                                                                          |  .----------.  |  .----------------.
                                                                                                          '->| Fake DNS |  '->| Captive portal |
                                                                                                             '----------'     '----------------'  
       
   
This YunoHost app is a part of the "[La Brique Internet](http://labriqueinter.net)" project but can be used independently.

## Prerequisites

* Debian Jessie
* YunoHost >= 2.2.0
* [Hotspot app for YunoHost](https://github.com/labriqueinternet/hotspot_ynh)
