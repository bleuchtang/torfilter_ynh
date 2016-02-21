#!/bin/bash

# TorFilter app for YunoHost
# Copyright (C) 2015 Emile morel <emile@bleuchtang.fr>
# Contribute at https://github.com/labriqueinternet/torfilter_ynh
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

set -e
set -x
IP_TORNODES='/etc/tor/ip_tor_nodes'
IP_TORPROJECT='/etc/tor/ip_tor_project'

/usr/bin/torify /usr/bin/python2.7 /usr/local/bin/get_tor_nodes.py "${IP_TORNODES}_tmp" 

if [ $? -ne 0 ]
then
  log "error with creating a tor nodes ipset"
  exit 1
else 
  mv "${IP_TORNODES}_tmp" ${IP_TORNODES}
fi

ipset -exist -N tornodesip hash:ip --probes 8
ipset -exist -N tmpip hash:ip --probes 8
ipset flush tmpip

for IP in $(cat "${IP_TORNODES}")
do
    ipset -exist add tmpip ${IP}
done
ipset swap tmpip tornodesip
ipset destroy tmpip

host torproject.org | grep 'torproject.org has address' \
  | sed 's/torproject.org has address //' > "${IP_TORPROJECT}_tmp" 
host tails.boum.org | grep 'tails.boum.org has address' \
  | sed 's/tails.boum.org has address //' >> "${IP_TORPROJECT}_tmp" 
host dl.amnesia.boum.org | grep 'dl.amnesia.boum.org has address' \
  | sed 's/dl.amnesia.boum.org has address //' >> "${IP_TORPROJECT}_tmp" 

if [ $? -ne 0 ]
then
  log "error with creating a tor project ipset"
  exit 1
else 
  mv "${IP_TORPROJECT}_tmp" ${IP_TORPROJECT}
fi

ipset -exist -N torprojectip hash:ip
ipset -exist -N tmpip hash:ip
ipset flush tmpip

for IP in $(cat "${IP_TORPROJECT}")
do
    ipset -exist add tmpip ${IP}
done
ipset swap tmpip torprojectip
ipset destroy tmpip

exit 0
