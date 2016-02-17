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
MACIP_LOCAL='/etc/tor/allow_mac'

ipset -exist -N localmacip macipmap --network 10.0.0.0/16 
ipset -exist -N tmpmacip macipmap --network 10.0.0.0/16

for MAC in $(cat "${MACIP_LOCAL}")
do
   ipset -exist --add tmpmacip $MAC 
done

ipset swap tmpmacip localmacip
ipset destroy tmpmacip
