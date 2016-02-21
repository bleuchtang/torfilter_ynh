<?php

/* Tor Client app for YunoHost 
 * Copyright (C) 2015 Émile Morel <emile@bleuchtang.fr>
 * Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
 * Contribute at https://github.com/labriqueinternet/torfilter_ynh
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function ynh_setting_get($setting, $app = 'torfilter') {
  $value = exec("sudo grep \"^$setting:\" /etc/yunohost/apps/$app/settings.yml");
  $value = preg_replace('/^[^:]+:\s*["\']?/', '', $value);
  $value = preg_replace('/\s*["\']$/', '', $value);

  return htmlspecialchars($value);
}

function get_macip($prefix) {
  /* 1455752036 ee:ee:ee:ee:ee:ee 10.0.242.8 android-4444444444444444 * */
  return exec("sudo cat /var/lib/misc/dnsmasq.leases | grep $prefix | cut -d' ' -f 2-4", $output, $retcode);

}

function ynh_setting_set($setting, $value) {
  return exec('sudo yunohost app setting torfilter '.escapeshellarg($setting).' -v '.escapeshellarg($value));
}

function stop_service() {
  exec('sudo systemctl stop ynh-torfilter');
}

function start_service() {
  exec('sudo systemctl start ynh-torfilter', $output, $retcode);

  return $retcode;
}

function service_status() {
  exec('sudo ynh-torfilter status', $output);

  return $output;
}

function service_faststatus() {
  exec('sudo systemctl is-active ynh-torfilter', $output, $retcode);

  return $retcode;
}

dispatch('/', function() {
  $ssids = explode('|', ynh_setting_get('wifi_ssid', 'hotspot'));
  $prefix = explode('|', ynh_setting_get('ip4_nat_prefix', 'hotspot'));
  $wifi_device_id = ynh_setting_get('wifi_device_id');
  $macip_set = ynh_setting_get('macip');
  $wifi_ssid_list = '';
  $wifi_ssid = '';
  $wifi_prefix = $prefix[$wifi_device_id];
  $maciplist = get_macip($wifi_prefix);

  for($i = 0; $i < count($ssids); $i++) {
    $active = '';

    if($i == $wifi_device_id) {
      $active = 'class="active"';
      $wifi_ssid = htmlentities($ssids[$i]);
    }

    $wifi_ssid_list .= "<li $active data-device-id='$i'><a href='javascript:;'>".htmlentities($ssids[$i]).'</a></li>';
  }

  $lines = split("\n", $maciplist);
  $macip = '';
  foreach ($lines as $line) {
    $ip_mac = explode(' ', $line); 
    if ($ip_mac[1] != NULL) {
      if (preg_match("/$ip_mac[0]/i", $macip_set)) {
        $macip .= "<div class=\"checkbox\"><label><input type='checkbox' name='check_list[]' value='$ip_mac[1],$ip_mac[0]' checked>".$ip_mac[0]." ".$ip_mac[2]."</label></div>";
      } else {
        $macip .= "<div class=\"checkbox\"><label><input type='checkbox' name='check_list[]' value='$ip_mac[1],$ip_mac[0]' >".$ip_mac[0]." ".$ip_mac[2]."</label></div>";
      }
    }  
  }

  set('faststatus', service_faststatus() == 0);
  set('service_enabled', ynh_setting_get('service_enabled'));
  set('wifi_device_id', $wifi_device_id);
  set('wifi_ssid', $wifi_ssid);
  set('maciplist', $macip);
  set('wifi_ssid_list', $wifi_ssid_list);

  return render('settings.html.php');
});

dispatch_put('/settings', function() {
  $service_enabled = isset($_POST['service_enabled']) ? 1 : 0;

  if($service_enabled == 1) {
    try {
      if($_POST['wifi_device_id'] == -1) {
        throw new Exception(_('You need to select an associated hotspot'));
      }

    } catch(Exception $e) {
      flash('error', $e->getMessage().' ('._('configuration not updated').').');
      goto redirect;
    }
  }

  $macip = '';
  foreach($_POST['check_list'] as $checkbox) {
    $macip .= "$checkbox|";
  }
#
#  foreach($_POST['check_list'] as $checkbox) {
#    file_put_contents('/tmp/mac_ip', $checkbox."\r\n");
#  }


  stop_service();

  ynh_setting_set('macip', $macip);
  ynh_setting_set('service_enabled', $service_enabled);

  if($service_enabled == 1) {
    ynh_setting_set('wifi_device_id', $_POST['wifi_device_id']);

     $retcode = start_service();

    if($retcode == 0) {
      flash('success', _('Configuration updated and service successfully reloaded'));
    } else {
      flash('error', _('Configuration updated but service reload failed'));
    }

  } else {
      flash('success', _('Service successfully disabled'));
  }

  redirect:
  redirect_to('/');
});

dispatch('/status', function() {
  $status_lines = service_status();
  $status_list = '';

  foreach($status_lines AS $status_line) {
    if(preg_match('/^\[INFO\]/', $status_line)) {
      $status_list .= '<li class="status-info">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[OK\]/', $status_line)) {
      $status_list .= '<li class="status-success">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[WARN\]/', $status_line)) {
      $status_list .= '<li class="status-warning">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[ERR\]/', $status_line)) {
      $status_list .= '<li class="status-danger">'.htmlspecialchars($status_line).'</li>';
    }
  }

  echo $status_list;
});

dispatch('/lang/:locale', function($locale = 'en') {
  switch($locale) {
    case 'fr':
      $_SESSION['locale'] = 'fr';
    break;

    default:
      $_SESSION['locale'] = 'en';
  }

  redirect_to('/');
});
