<!--
  Tor Client app for YunoHost 
  Copyright (C) 2015 Émile Morel <emile@bleuchtang.fr>
  Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
  Contribute at https://github.com/labriqueinternet/torfilter_ynh
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.
  
  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<h2><?= _("Tor Client Configuration") ?></h2>
<?php if($faststatus): ?>
  <span class="label label-success" data-toggle="tooltip" data-title="<?= _('This is a fast status. Click on More details to show the complete status.') ?>"><?= _('Running') ?></span>
<?php else: ?>
  <span class="label label-danger" data-toggle="tooltip" data-title="<?= _('This is a fast status. Click on More details to show the complete status.') ?>"><?= _('Not Running') ?></span>
<?php endif; ?>

 &nbsp; <img src="public/img/loading.gif" id="status-loading" alt="Loading..." /><a href="#" id="statusbtn" data-toggle="tooltip" data-title="<?= _('Loading complete status may take a few minutes. Be patient.') ?>"><?= _('More details') ?></a>

<div id="status" class="alert alert-dismissible alert-info fade in" style="margin-top: 10px" role="alert">
  <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <div id="status-text"></div>
</div>

<hr />

<div class="row">
  <div class="col-sm-offset-2 col-sm-8">
    <form method="post" enctype="multipart/form-data" action="?/settings" class="form-horizontal" role="form" id="form">
      <input type="hidden" name="_method" value="put" />

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= _("Service") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
  
          <div class="form-group">
            <label for="service_enabled" class="col-sm-3 control-label"><?= _('Tor Enabled') ?></label>
            <div class="col-sm-9 input-group-btn">
              <div class="input-group">
                <input type="checkbox" class="form-control switch" name="service_enabled" id="service_enabled" value="1" <?= $service_enabled == 1 ? 'checked="checked"' : '' ?> />
              </div>
            </div>
          </div>

          <div class="form-group">
            <?php if($wifi_device_id == -1): ?>
              <div class="alert alert-dismissible alert-warning fade in" style="margin: 2px 16px 17px" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong><?= _('Notice') ?>:</strong> <?= _("You need to select an associated hotspot.") ?>
              </div>
            <?php endif; ?>

            <label for="wifi_device_id" class="col-sm-3 control-label"><?= _('Associated Hotspot') ?></label>
            <div class="col-sm-9 input-group-btn">
              <div class="input-group">
                  <input type="text" name="wifi_device_id" id="wifi_device_id" value="<?= $wifi_device_id ?>" style="display: none" />
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?= empty($wifi_ssid) ? '<em>'._("None").'</em>' : $wifi_ssid ?> <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-left" id="deviceidlist" role="menu">
                    <?= $wifi_ssid_list ?>
                  </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="panel-heading">
          <h3 class="panel-title"><?= _("Device connected on the torfilter wifi") ?></h3>
        </div>
	  By checking the boxes corresponding to your devices, you authorize them to connect to the internet through the Tor client of your internetcube (without a tor sofware installed on it device). 
          <div class="checkbox">
             
                  <?= empty($maciplist) ? '' : $maciplist ?></br>
          <div>

      </div>
      <div class="form-group">
        <div style="text-align: center">
          <button type="submit" class="btn btn-default" data-toggle="tooltip" id="save" data-title="<?= _('Reloading may take a few minutes. Be patient.') ?>"><?= _('Save and reload') ?></button> <img src="public/img/loading.gif" id="save-loading" alt="Loading..." />
        </div>
      </div>
    </form>
  </div>
</div>
