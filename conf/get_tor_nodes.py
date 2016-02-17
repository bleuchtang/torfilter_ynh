#!/usr/bin/env python

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

from stem.descriptor.remote import DescriptorDownloader
import yaml
import sys

downloader = DescriptorDownloader()

try:
  desc = downloader.get_consensus().run()
except Exception as exc:
  print("Unable to retrieve the consensus: %s" % exc)

f = open(sys.argv[1], 'w+')
for d in desc: 
  f.write("%s\n" % d.address)
f.close()
