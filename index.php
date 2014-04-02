<?php
/***************************
* Urban Assault Level Generator – Script to generate levels for Microsoft's Urban Assault Game
* Copyright © 2014 Daniel Portales Rosado
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
***************************/

// TODO
// - Crear array con factions presentes para no hacer verificaciones "Si faction está presente" después de seleccionar raza con rand
// - Crear funcion rx y ry para obtener valor random de posició dentro del mapa

class Urbanassault {
  var $fh;
  var $debug = false;
  var $levels = array(1,2,3,4,5,10,11,12,15,20,21,22,23,25,26,30,31,32,33,34,40,41,42,43,44,50,51,52,53,54,60,61,62,63,64,66,70,71,72,73,74,75); #DEBUG: 98, 99 
  var $level_childs = array(
    1     => array(2,3),      # Central Park
    2     => array(4),        # Virgin Steel
    3     => array(5),        # Skull Alley
    4     => array(10),       # Checker Board
    5     => array(11,12),    # Surprise!
    10    => array(20),       # Labyrinth
    11    => array(21),       # Dark Valley
    12    => array(22,23),    # Fort Grenada
    15    => array(),         # Parasite City
    20    => array(30,34),    # Hard Thing
    21    => array(31),       # Drak
    22    => array(32),       # Pedestal Mountain
    23    => array(33),       # Taerkastik
    25    => array(),         # Training 1
    26    => array(),         # Training 2
    30    => array(40),       # No Man's Land
    31    => array(41),       # Hurz 
    32    => array(42),       # Great Confusion
    33    => array(43),       # Stonehenge
    34    => array(40,44),    # Two Hills
    40    => array(50,51),    # Moonlight City
    41    => array(52),       # Wide Field
    42    => array(53),       # Peacemaker
    43    => array(54),       # Command and Taerkast
    44    => array(50),       # Assi's Way
    50    => array(60),       # Death Valley
    51    => array(66),       # Dark Zone
    52    => array(61,62),    # Hamburger Hill
    53    => array(63),       # Sulog's Rising
    54    => array(64),       # Sulog's Fine Thread
    60    => array(70),       # Mykophobia
    61    => array(75),       # Sibuna
    62    => array(72),       # Maze Wayz
    63    => array(73),       # Blue Casbah
    64    => array(74),       # Taer or Black Wadi
    66    => array(71),       # Gaunlet
    70    => array(15),       # Desert of Madness
    71    => array(15),       # Smile
    72    => array(15),       # Industrial Centre
    73    => array(15),       # Slaughter Field
    74    => array(15),       # Mission Possible
    75    => array(15)        # The Loong Way
  ); #DEBUG: 98, 99 

  var $slots = array(
    1 => array(0,1,2,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,40,41,44,50,51,59,67,70,71,72,74,75,76,77,78,80,81,82,95,96,97,98,99,100,130,131,132,133,134,135,136,137,138,139,150,151,153,154,155,157,159,160,161,162,163,164,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,198,232,233,234,235,236),
    2 => array(0,1,2,5,6,7,8,9,11,12,13,16,17,18,19,20,21,22,23,24,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,65,66,67,68,69,70,71,72,75,76,77,78,80,81,82,83,87,89,90,92,93,94,95,97,99,100,120,121,122,123,124,125,126,127,128,129,130,131,133,150,151,152,153,155,158,159,160,161,162,163,167,168,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,198,199,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225),
    3 => array(0,1,2,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,40,41,44,59,67,69,70,71,72,74,75,76,77,78,80,81,82,100,130,131,132,133,134,135,136,137,138,139,150,151,152,153,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189),
    4 => array(0,1,2,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,40,41,59,60,66,67,69,70,71,72,74,75,76,77,78,80,81,82,130,131,132,133,134,135,136,137,138,139,140,141,150,151,153,155,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189),
    5 => array(0,1,2,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,57,58,59,62,63,65,66,67,68,69,70,71,72,75,76,78,79,82,84,87,92,93,94,95,97,98,99,105,106,107,108,109,113,114,120,121,122,123,124,125,126,127,128,129,130,131,133,134,135,150,151,152,153,155,157,158,159,161,160,162,163,166,167,168,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,190,191,198,199,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225),
    6 => array(0,1,2,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,40,41,44,59,66,67,68,70,71,72,74,75,76,77,78,79,80,81,82,95,96,97,98,99,130,131,132,133,134,135,136,137,138,139,140,141,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,188,189,228,229,230,231,232,233,234,248)
  );

  var $vehicles = array(
    'res' => array(1,2,3,4,5,6,7,  9,10,11,12,  14,15,16), #134,135 Resistance Broinjest and Giant
    'sul' => array(71,72,73,74),
    'myk' => array(63,64,65,66,67,68,69,70),
    'tae' => array(8,  32,33,34,35,36,37,38,  131),
    'bla' => array(),
    'gho' => array(22,23,24,25,26,27,28,29,30,31,  130)
  );

  var $buildings = array(
    'res' => array(11,63,2,28,3), # Only tier 1 buildings
    'sul' => array(10),
    'myk' => array(10,13,72), # 33, 34
    'tae' => array(17,31,53,73), # 20, 21
    'bla' => array(18,1,54,64), # 1,54,64 = Resistance upgraded buildings
    'gho' => array(30,52,12,71), # 22, 8, 60, 18
  );

  var $skies = array('1998_01', '1998_02', '1998_03', '1998_05', '1998_06', 'Am_1', 'Am_2', 'Am_3', 'Arz1', 'Asky2', 'Braun1', 'Ct6', 'H', 'H7', 'Haamitt1', 'Haamitt4', 'Mod2', 'Mod4', 'Mod5', 'Mod7', 'Mod8', 'Mod9', 'Moda', 'Modb', 'Nacht1', 'Nacht2', 'Newtry5', 'Nosky', 'Nt1', 'Nt2', 'Nt3', 'Nt5', 'Nt6', 'Nt7', 'Nt8', 'Nt9', 'Nta', 'S3_1', 'S3_4', 'Smod1', 'Smod2', 'Smod3', 'Smod4', 'Smod5', 'Smod6', 'Smod7', 'Smod8', 'Sterne', 'wow1', 'wow5', 'wow7', 'wow8', 'wow9', 'wowa', 'wowb', 'wowc', 'wowd', 'wowe', 'wowf', 'wowh', 'wowi', 'wowj', 'x1', 'x2', 'x4', 'x5', 'x7', 'x8', 'x9', 'xa', 'xb', 'xc');
  var $size_x = 0, $size_y = 0;
  
  var $factions = array(2 => 'sul','myk','tae','bla','gho');
  var $map = array();

  var $num_hosts;
  var $host_x;
  var $host_y;

  function __construct($mode = 'campaing'){
    if($this->debug) $mode = 'single';

    // 'Steal' vehicles and buildings for black sect 
    foreach ($this->factions as $faction) {
      if($faction != 'bla'){
        $this->vehicles['bla'] = array_merge($this->vehicles['bla'], $this->vehicles[$faction]);
        $this->buildings['bla'] = array_merge($this->buildings['bla'], $this->buildings[$faction]);
      }
    }

    if(is_string($mode)){
      if($mode == 'single')
        $levels = array(1);
      elseif ($mode == 'campaing') {
        $levels = $this->levels;
      }
    } elseif (is_array($mode)) {
      $levels = $mode;
    }


    foreach ($levels as $level_id) {
      $this->make_level($level_id);
    }
  }

  function init($level_id){ # Initializer for each level
    $this->level_id = $level_id;

    $this->startup_value = '2';
    if($this->level_id == 1)
      $this->startup_value = '';

    $this->fh = fopen('L'.sprintf('%02d%02d',$this->level_id,$this->level_id).'.ldf', 'w');

    $this->num_hosts = array_fill_keys($this->factions, 0);
    $this->host_x = array_fill_keys($this->factions, array());
    $this->host_y = array_fill_keys($this->factions, array());
  }
  
  function fid($faction){ return $faction == 'res' ? 1 : array_search($faction, $this->factions); }
  function x($x){ return (($x + 0.5) * 1200) + 1; /* + 1 to avoid bugs on sector edge */ }
  function y($y){ return (($y + 0.5) * -1200) + 1; /* + 1 to avoid bugs on sector edge */ }
  function rx(){ return rand(1, $this->size_x - 2); }
  function ry(){ return rand(1, $this->size_y - 2); }
  function present_factions(){ return array_keys(array_filter($this->num_hosts)); }
  function pick($arr){ return $arr[array_rand($arr)]; }
  function reset_map($reset_value = 0){ $this->map = array_fill(0, $this->size_x, array_fill(0, $this->size_y, $reset_value)); }
  function new_height($height){
    $height += rand(0, 4) - 2;
    if($height < 0) $height = 0;
    if($height > 255) $height = 255;

    return $height;
  }

  function make_level($level_id = 1){
    $this->init($level_id);

    if($this->debug){
      echo '<pre>';
    } else {
      ob_start();
    }

    do {
      // Calculate map size (min 3, max 32 by side)
      $this->size_x = rand(0, 29) + 3;
      $this->size_y = rand(0, 29) + 3;
    } while($this->size_x * $this->size_y < 64); // Make sure map size is not too small (64 u2 at least)

    $this->set = rand(1, 6); // Map type
?>
begin_level
  set     = <?=$this->set ?> 
  sky     = objects/<?=$this->pick($this->skies) ?>.base
  slot0   = palette/standard.pal
  slot1   = palette/red.pal
  slot2   = palette/blau.pal
  slot3   = palette/gruen.pal
  slot4   = palette/inverse.pal
  slot5   = palette/invdark.pal
  slot6   = palette/sw.pal
  slot7   = palette/invtuerk.pal
end

; Mission Briefing Map
begin_mbmap
  name = MB_15.IFF
end

; Mission Debriefing Map
begin_dbmap
  name = DB_15.IFF
end

; Beam Gates
<?php
    foreach ($this->level_childs[$this->level_id] as $target_level) {
?>
begin_gate
  sec_x         = <?=$this->rx()?> 
  sec_y         = <?=$this->ry()?> 
  closed_bp     = 5
  opened_bp     = 6
  target_level  = <?=$target_level ?> 
<?php
      for($ks = 0; $ks < 6; $ks++){
        if(rand(0, 1)){
?>
  keysec_x      = <?=$this->rx() ?> 
  keysec_y      = <?=$this->ry() ?> 
<?php
        }
      }
?>
  mb_status     = unknown 
end
<?php      
    }
?>

; Player Host Station
<?php
    $resistance_x = $this->x($this->host_x['res'][0] = $this->rx());
    $resistance_y = $this->y($this->host_y['res'][0] = $this->ry());
    $energy       = (6 + rand(0, 6)) * 100000; // x 100,000 / 4 = [1200 - 3000]
    $reload_const = floor(((($energy - 550000)/4) + 550000) /4); // Drak constant = 550,000
?>
begin_robo
  owner         = 1 
  vehicle       = 56 
  pos_x         = <?=$resistance_x ?> 
  pos_y         = <?= -10 * (20 + rand(0, 25)) ?> 
  pos_z         = <?=$resistance_y ?> 
  energy        = <?=$energy ?> 
  reload_const  = <?=$reload_const ?> 
end 

; Enemy Host Stations
<?php
    $num_hosts_ideal = $this->size_x * $this->size_y * 3/144; // Calculates ideal HostStation number for map

    $this->add_host_station(); // At least one enemy HostStation

    // Keep trying to add HostStations until there are more than 5 Stations or number of tries have been more than ideal number of Stations
    for($hs = 0; $hs < $num_hosts_ideal && array_sum($this->num_hosts) < 6; $hs++){
      if(rand(0,1)) # 50 - 50 chance
        $this->add_host_station();
    }

    ///// Stoudson Bomb
    for ($sb = 0; $sb < 2; $sb++){ // 2 bombs max
      if(rand(0,1)){
?>
; Stoudson Bomb
begin_item
  sec_x         = <?=$this->rx() ?> 
  sec_y         = <?=$this->ry() ?> 
  inactive_bp   = <?=$this->set == 6 ? 68 : 35 ?> 
  active_bp     = <?=$this->set == 6 ? 69 : 36 ?> 
  trigger_bp    = <?=$this->set == 6 ? 70 : 36 ?> 
  type          = 1 
  countdown     = <?=(rand(0, 2500) + 20) * 1000 ?> ;20 seconds - 42 minutes 
<?php
        for ($ks = 0; $ks < 10; $ks++){
          if (rand(0,1)){
?>
  keysec_x      = <?=$this->rx()?> 
  keysec_y      = <?=$this->ry()?> 
<?php
          }
        }
?>
end 

<?php
      }
    }
?>
;Predefined Squads
<?php
    ///// Predefined Squads

    // 3 squads per faction max
    $total_hosts = array_sum($this->num_hosts);

    for ($sq = 0; $sq <  3 * $total_hosts; $sq++){
      if (rand(0, 1)){
        $this->add_squad(); //Squad creation
      }
    }
?>
; Prototype Modifications
include data:scripts/startup<?=$this->startup_value?>.scr

<?php
    ///// Prototype Enabling
    $present_factions = array_merge(array('res'),$this->present_factions());
    foreach ($present_factions as $faction) {
      $fid = $this->fid($faction);
?>
begin_enable <?=$fid?> 
<?php
      // Enable vehicles
      foreach ($this->vehicles[$faction] as $vehicle) {
?>
  vehicle   = <?=$vehicle?> 
<?php
      }

      // Enable buildings
      foreach ($this->buildings[$faction] as $building) {
?>
  building  = <?=$building?> 
<?php
      }
?>
end ; end of enable

<?php
    }
        
    ///// Tech
    ///// Map Dumps
?>
begin_maps 
  typ_map = 
    <?=$this->size_x.' '.$this->size_y ?>
<?php
    ///// TYP
    # top
    echo "\n    f8 ";
    for($px = 0; $px < $this->size_x - 2; $px++){ echo 'fc '; }
    echo 'f9';

    # body
    for($py = 0; $py < $this->size_y - 2; $py++){
      echo "\n    ff ";
      for($px = 0; $px < $this->size_x - 2; $px++){ printf("%02x ", $this->pick($this->slots[$this->set])); }
      echo 'fd';
    }

    # bottom
    echo "\n    fb ";
    for ($px = 0; $px < $this->size_x - 2; $px++){ echo 'fe '; }
    echo 'fa';

?> 

  own_map =
    <?=$this->size_x.' '.$this->size_y ?>
<?php    
    ///// OWN
    # Initialized in 7 = Neutral
    $this->reset_map();
    # Ideal number of sectors per race = playable area / number of stations + 60%
    $this->ideal_territory = ($this->size_x - 2) * ($this->size_y - 2) / array_sum($this->num_hosts) * 1.6;

    # Mapear
    $present_factions = array_merge(array('res'), $this->present_factions());

    foreach($present_factions as $faction){
      $this->make_territory($faction);
    }

    # Outside sectors to 0
    for ($px = 0; $px < $this->size_x; $px++){
      $this->map[$px][$this->size_y - 1] = $this->map[$px][0] = 0;
    }

    for ($py = 0; $py < $this->size_y; $py++){
      $this->map[$this->size_x - 1][$py] = $this->map[0][$py] = 0;
    }

    # Imprimir mapa
    for($py = 0; $py < $this->size_y; $py++){
      echo "\n    ";
      for($px = 0; $px < $this->size_x; $px++){ printf("%02d ", $this->map[$px][$py]); }
    }
?> 

  hgt_map =
    <?=$this->size_x.' '.$this->size_y ?>
<?php
    ///// HGT
    $height = 25 + rand(0, 50); # base height

    $this->reset_map($height);

    for ($py = 1; $py < $this->size_y - 1; $py++){
      for ($px = 1; $px < $this->size_x - 1; $px++){
        $this->map[$px][$py] = $height;
        $this->set_adjacent_height($height, $px, $py);

        # New base height
        $height += rand(0, 4) - 2;
      }
    }

    ////// Even top and bottom outside sectors
    for ($px = 0; $px < $this->size_x; $px++){
      $this->map[$px][0] = $this->map[$px][1];
      $this->map[$px][$this->size_y - 1] = $this->map[$px][$this->size_y - 2];
    }

    ////// Even left and right outside sectors
    for ($py = 0; $py < $this->size_y; $py++){
      $this->map[0][$py] = $this->map[1][$py];
      $this->map[$this->size_x - 1][$py] = $this->map[$this->size_x - 2][$py];
    }

    for ($py = 0; $py < $this->size_y; $py++){
      echo "\n    ";
      for ($px = 0; $px < $this->size_x; $px++)
        printf("%02x ", $this->map[$px][$py]);
    }
?> 

  blg_map =
    <?=$this->size_x.' '.$this->size_y ?>
<?php
    $this->reset_map();
    
    // Giving PowerStation to EnemyStations
    $present_factions = $this->present_factions();
    foreach ($present_factions as $faction) {
      $faction_hosts = $this->num_hosts[$faction];
      
      for ($i = 0; $i < $faction_hosts; $i++) {
        $x = $this->host_x[$faction][$i];
        $y = $this->host_y[$faction][$i];

        switch ($faction) {
          case 'sul':
          case 'myk':
            $blg = 10;
          break;
          case 'tae':
            $blg = 17;
          break;
          case 'bla':
            $blg = 11;
          break;
          case 'gho':
            $blg = 12;
          break;
        }

        $this->map[$x][$y] = $blg;

      }
    }

    for ($py = 0; $py < $this->size_y; $py++){
      echo "\n    ";
      for($px = 0; $px < $this->size_x; $px++)
        printf("%02x ", $this->map[$px][$py]);
    }
?> 
end ; end of maps
<?php   
    
    ///// Output
    fwrite($this->fh, ob_get_contents()); // From buffer to file
    fclose($this->fh);
    
    if(!$this->debug){ ob_end_clean(); }
  }

  //---------------------------------------------

  function add_host_station(){
    $host_vehicles = array('sul' => 61, 'myk' => 58, 'tae' => 60, 'bla' => 62);
    $station_added = false;

    do {
      $faction = $this->pick($this->factions); // Select random faction
      
      if($this->num_hosts[$faction] < 2) { // 2 Stations max per faction
        $new_station_position = $this->distribute_host($faction); // Search optimal position for HostStation
        
        if($new_station_position === false){
          return;
        
        } else {
          list($x, $y) = $new_station_position; // Search optimal position for HostStation
          $this->host_x[$faction][] = $x;
          $this->host_y[$faction][] = $y;

          $this->num_hosts[$faction]++;
          $station_added = true;

          $energy = (80 + rand(0, 60)) * 10000; // HostStation energy (min 2000, max 3500)
          $reload_const = floor((($energy - 500000)/ 3) + 500000); // Drak constant = 500,000

          if($faction == 'gho')
            $host_vehicle = rand(0, 1) ? 59 : 57; // Variable
          else
            $host_vehicle = $host_vehicles[$faction];
?>
begin_robo
  owner         = <?=$this->fid($faction) ?> 
  vehicle       = <?=$host_vehicle ?> 
  pos_x         = <?=$this->x($x) ?> 
  pos_y         = <?=(20 + rand(0, 25)) * -10 ?> 
  pos_z         = <?=$this->y($y) ?> 
  energy        = <?=$energy ?> 
  reload_const  = <?=$reload_const ?> 
  con_budget    = <?=rand(0, 50) + 50    ?> 
  con_delay     = <?=rand(0, 75) * 1000  ?> 
  def_budget    = <?=rand(0, 40) + 60    ?> 
  def_delay     = <?=rand(0, 75) * 1000  ?> 
  rec_budget    = <?=rand(0, 50) + 50    ?> 
  rec_delay     = <?=rand(0, 75) * 1000  ?> 
  rob_budget    = <?=rand(0, 50) + 50    ?> 
  rob_delay     = <?=rand(0, 75) * 1000  ?> 
  pow_budget    = <?=rand(0, 60) + 40    ?> 
  pow_delay     = <?=rand(0, 75) * 1000  ?> 
  rad_budget    = <?=rand(0, 20) + 10    ?> 
  rad_delay     = <?=rand(0, 75) * 1000  ?> 
  saf_budget    = <?=rand(0, 50) + 50    ?> 
  saf_delay     = <?=rand(0, 75) * 1000  ?> 
  cpl_budget    = <?=rand(0, 70) + 30    ?> 
  cpl_delay     = <?=rand(0, 75) * 1000  ?> 
end 

<?php
        }
      }
    } while (!$station_added); // Intentar crear de nuevo una HostStation
  }

  //------------------------------------------

  function distribute_host($new_station_faction){
    $tries = 0;

    do {
      $test_x = $this->rx();
      $test_y = $this->ry();
      $invalid_position = false;
      $present_factions = array_merge(array('res'), $this->present_factions());

      foreach($present_factions as $faction){
        // if($faction != $new_station_faction){ // Verify just against Enemy
          if($faction == 'res')
            $faction_hosts = 1;
          else
            $faction_hosts = $this->num_hosts[$faction];

          for($c = 0; $c < $faction_hosts; $c++){
            if(sqrt(pow($this->host_x[$faction][$c] - $test_x, 2) + pow($this->host_y[$faction][$c] - $test_y, 2)) < 4){ // Too close
              $invalid_position = true;
              break;
            }
          }
          
          if($invalid_position) break;
        // }
      }
    } while($invalid_position && $tries++ && $tries < 256);

    return $invalid_position ? false : array($test_x, $test_y);
  }

  //--------------------------------

  function add_squad(){
    $faction = $this->pick($this->present_factions());
    $vehicle = $this->pick($this->vehicles[$faction]);

    if(in_array($vehicle, array(9,74,67,35,29))) # If it's a scout
      $squad_size = 1;
    else
      $squad_size = rand(1,12);

    // Don't locate squads on same sector as enemy Host Stations
    do {
      $invalid_position = false;
      $pos_x = $this->rx();
      $pos_y = $this->ry();

      foreach ($this->present_factions() as $target_faction) {
        if($faction != $target_faction){ // Just for enemies
          for ($c = 0; $c < $this->num_hosts[$faction]; $c++) {
            $host_pos_x = $this->host_x[$faction][$c];
            $host_pos_y = $this->host_y[$faction][$c];

            if($host_pos_x == $pos_x && $host_pos_y == $pos_y){
              $invalid_position = true;
              break;
            }
          }
        }

        if($invalid_position) break;
      }

    }while($invalid_position);

    $mb_status = '';

    if(rand(0, 2))
      $mb_status = "  mb_status = unknown\r\n";
?>
begin_squad
  owner     = <?=$this->fid($faction) ?> 
  vehicle   = <?=$vehicle ?> 
  num       = <?=$squad_size ?> 
  pos_x     = <?=$this->x($pos_x) ?> 
  pos_z     = <?=$this->y($pos_y) ?> 
<?=$mb_status ?>
end 

<?php
  }

  //---------------------------------

  function make_territory($faction){
    $faction_hosts = $this->num_hosts[$faction];
    $fid = $this->fid($faction);
    
    for($fh = 0; $fh < $faction_hosts; $fh++){
      # Stations coordinates
      $x = $this->host_x[$faction][$fh];
      $y = $this->host_y[$faction][$fh];
      
      $this->adjacent_territory($fid, $x, $y); 

      for($i = 0; $i < $this->ideal_territory; $i++){
        if (rand(0, 1)){
          # Elige nuevo pivote
          $x = $x + (rand(0, 2) - 1);
          $y = $y + (rand(0, 2) - 1);

          if($x < 1) $x = 1;
          if($x > $this->size_x - 1) $x = $this->size_x - 1;

          if($y < 1) $y = 1;
          if($y > $this->size_y - 1) $y = $this->size_y - 1;

          $this->adjacent_territory($fid, $x, $y);
        }
      }
    }
  }

  # Sets the values around the pivot
  function adjacent_territory($fid, $x, $y){
    $this->map[$x - 1][$y - 1]  = $fid;
    $this->map[$x - 1][$y]      = $fid;
    $this->map[$x][$y - 1]      = $fid;
    $this->map[$x + 1][$y + 1]  = $fid;
    $this->map[$x + 1][$y]      = $fid;
    $this->map[$x][$y + 1]      = $fid;
    $this->map[$x + 1][$y - 1]  = $fid;
    $this->map[$x - 1][$y + 1]  = $fid;
  }

  //---------------------------------------------------------------------

  function set_adjacent_height($height, $x2, $y2){
    $this->map[$x2 - 1][$y2]      = $this->new_height($height);
    $this->map[$x2][$y2 - 1]      = $this->new_height($height);
    $this->map[$x2 + 1][$y2 + 1]  = $this->new_height($height);
    $this->map[$x2 + 1][$y2]      = $this->new_height($height);
    $this->map[$x2][$y2 + 1]      = $this->new_height($height);
    $this->map[$x2 + 1][$y2 - 1]  = $this->new_height($height);
    $this->map[$x2 - 1][$y2 + 1]  = $this->new_height($height);
  }
}

new Urbanassault(); 
?>