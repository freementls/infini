<?php

$categories = array(
'quality' => array('inferior', 'normal', 'superior', 'magic', 'unique', 'set'),
'variability' => array('static', 'functional'),
'function' => array('constant', 'linear', 'sinusoidal', 'step', 'sawtooth'),
'object_type' => array('equipable', 'consumable', 'ingredient', 'equipment enhancer', 'inventory', 'world'),
'property' => array('unrepairable', 'sockets', 'socketable', 'strength', 'dexterity', 'intelligence', 'vitality', 'attack speed'),
'consumability' => array('never', 'temporary', 'permanent', 'hidden'),
'in_game_variables' => array('stats', 'items', 'charges', 'skills', 'experience', 'health'),
);


// variability categories
$variability = array('static', 'functional');

$functions = array('constant', 'linear', 'sinusoidal', 'step', 'sawtooth');

$item_types = array('equipment', 'consumable', 'ingredient', 'equipment enhancer', 'inventory');

// low, medium, high, temporarily consumable, permanently consumable: stats, items, charges, spells, experience, health

// stat classes? (primary, secondary)

/*$item_stats = array( // description, stat ranges, variability
array('+$1-$2 damage', array(array(15, 25), array(25, 35)), 1),
array('+$1-$2 damage', array(array(5, 15), array(35, 45)), 1),
array('+$1-$2 damage', array(array(1, 15), array(50, 65)), 1),
array('+$1-$2 armor', array(array(15, 25), array(25, 35)), 1),
array('+$1-$2 armor', array(array(5, 15), array(35, 45)), 1),
array('+$1-$2 armor', array(array(1, 15), array(50, 65)), 1),
array('+$1% damage', array(array(5, 15)), 0),
array('+$1 damage', array(array(1, 20)), 0),
array('+$1 damage', array(array(21, 40)), 0),
array('+$1 damage', array(array(41, 51)), 0),
array('+$1 armor', array(array(1, 20)), 0),
array('+$1 armor', array(array(21, 40)), 0),
array('+$1 armor', array(array(41, 51)), 0),
);*/

$item_stats = array( // description, stat ranges, variability
array('+$1-$2 Damage', array(array(1, 100), array(1, 100)), 1),
array('+$1% Attack Speed', array(array(1, 20)), 0),
array('+$1% Damage', array(array(1, 20)), 0),
array('+$1 Damage', array(array(1, 100)), 0),
array('+$1 Armor', array(array(1, 100)), 0),
array('+$1 Strength', array(array(1, 100)), 0),
array('+$1 Dexterity', array(array(1, 100)), 0),
array('+$1 Intelligence', array(array(1, 100)), 0),
array('+$1 Vitality', array(array(1, 100)), 0),
);

$item_base_damage_range = array(400, 600);

// stat generation

$item_qualities = array(
array(0, '#AAAAAA'), // inferior
array(0, '#CCCCCC'), // normal
array(0, '#FFFFFF'), // superior
array(1, '#0000AA'), // magic
array(2, '#0000CC'), // magic 2
array(3, '#0000FF'), // magic 3
array(1, '#AA0000'), // unique 1
array(2, '#CC0000'), // unique 2
array(3, '#FF0000'), // unique 3
array(1, '#00FF00'), // set 1
array(2, '#00FF00'), // set 2
array(3, '#00FF00'), // set 3
array(1, '#333333'), // unrepairable 1
array(2, '#666666'), // unrepairable 2
array(3, '#999999'), // unrepairable 3
);

$properties = array( // property name => property affinity, 
'unrepairable' => array(),
'sockets' => array(),
'socketable' => array(),
'strength' => array(),
'dexterity' => array(),
'intelligence' => array(),
'vitality' => array(),
'attack speed' => array(),
'movespeed' => array(),
);

$property_affinity = array(
'jewelry' => array( // should every stat by equally available on jewelry?

),
);

// inherent properties on some item classes? ex. plate mail slows you down
// property requirements for item classes
// alternate to global base item rarity; more popular in different game areas?
// different socketable effects based on item socketed into... not a terrible system but it could be expanded... can't see a reason why socketables can behave the same as equipables beyond reduction of base item stats while retaining same magic stats and interactions

armorclass
armorclass_vs_missile
armorclass_vs_hth
normal_damage_reduction
damageresist
item_armor_percent
magic_damage_reduction
strength
dexterity
vitality
energy
maxmana
item_maxmana_percent
maxhp
item_maxhp_percent
tohit
toblock
coldmindam
coldmaxdam
coldlength
firemindam
firemaxdam
lightmindam
lightmaxdam
poisonmindam
poisonmaxdam
poisonlength



item_damagetomana
fireresist
maxfireresist
lightresist
maxlightresist
coldresist
maxcoldresist
magicresist
maxmagicresist
poisonresist
maxpoisonresist
fireresist
maxfireresist
item_absorbfire_percent
item_absorbfire
item_absorblight_percent
item_absorblight
item_absorbmagic_percent
item_absorbmagic
item_absorbcold_percent
item_absorbcold
maxdurability
item_maxdurability_percent
hpregen
item_attackertakesdamage
item_fasterattackrate
item_fasterattackrate
item_fasterattackrate
item_goldbonus
item_magicbonus
item_knockback
staminarecoverybonus
manarecoverybonus
maxstamina
item_timeduration
manadrainmindam
lifedrainmindam
item_addclassskills
item_addclassskills
item_addclassskills
item_addclassskills
item_addclassskills
item_doubleherbduration
item_lightradius
item_lightcolor
item_req_percent
item_fastermovevelocity
item_fastermovevelocity
item_fastermovevelocity
item_fastergethitrate
item_fastergethitrate
item_fastergethitrate
item_fasterblockrate
item_fasterblockrate
item_fasterblockrate
item_fastercastrate
item_fastercastrate
item_fastercastrate
item_poisonlengthresist
item_normaldamage
item_howl
item_stupidity
item_ignoretargetac
item_fractionaltargetac
item_preventheal
item_halffreezeduration
item_tohit_percent
item_damagetargetac
item_demondamage_percent
item_undeaddamage_percent
item_demon_tohit
item_undead_tohit
item_throwable
item_elemskill
item_allskills
item_attackertakeslightdamage
item_freeze
item_openwounds
item_crushingblow
item_kickdamage
item_manaafterkill
item_healafterdemonkill
item_extrablood
item_deadlystrike
item_slow
item_cannotbefrozen
item_staminadrainpct
item_reanimate
item_pierce
item_magicarrow
item_explosivearrow


All Druid Skills
All Assassin Skills



Proc Skill on Swing
Proc Skill on Hit
Proc Skill on Get Hit
Increase chance of finding Gems




Fire Damage
Lightning Damage
Magic Damge
Cold Damage
Poison Damage
Throwing Damage
Normal Damage Modifier
AC per Player Level
AC% per Player Level
HP per Player Level
Mana per Player Level
Max Damage per Player Level
Max Damage % per Player Level
Strength per Player Level
Dexterity per Player Level
Energy per Player Level
Vitality per Player Level
Attack per Player Level
Attack% per Player Level
Max Cold Damage per Player Level
Max Fire Damage per Player Level
Max Lightning Dmg per Player Level
Max Poison Dmg per Player Level
Resist Cold% per Player Level
Resist Fire% per Player Level
Resist Lightning% per Player Level
Resist Poison% per Player Level
Absorb Cold Dmg per Player Level
Absorb Fire Dmg per Player Level
Absorb Lightning Dmg per Player Lvl
Absorb Poison Dmg per Player Lvl
Damage to Attacker per Player Lvl
+% Gold Dropped per Player Lvl
+% Magical per Player Lvl
+% Stamina Regeneration per Player Lvl
Stamina per Player Level
Damage to Demons % per Player Level
Damage to Undead % per Player Level
Attack Demons + per Player Level
Attack Undead + per Player Level
+% Chance of Crushing Blow per Player Level
+% Chance of Open Wounds per Player Level
Kick Damage per Player Level
+% Chance of Deadly Strike per Player Level
+% Chance of finding Gems per Player Level
regenerates durability
regenerates quantity
Increased stack size
+% Chance of finding item
Slashing Damage
Slashing Damage %
Crush Damage
Crush Damage %
Thrust Damage
Thrust Damage %
Absorb Slashing Damage
Absorb Crushing Damage
Absorb Thrusting Damage
Absorb Slashing Damage %
Absorb Crushing Damage %
Absorb Thrusting Damage %
AC / time increment (0=day, 1=dusk, 2=night, 3=dawn)
AC% / time increment (8 periods)
HP / time increment
Mana / time increment
Max Damage / time increment
Max Damage % / time increment
Strength / time increment
Dexterity / time increment
Energy / time increment
Vitality / time increment
To hit / time increment
To Hit % / time increment
Cold Damage Max / time inc.
Fire Damage Max / time inc.
Lightning Damage Max / time inc.
Poison Damage Max / time inc.
Resist Cold / time inc.
Resist Fire / time inc.
Resist Lightning / time inc
Resist Poison / time inc
Absorb Cold / time inc.
Absorb Fire / time inc.
Absorb Lightning / time inc.
Absorb Poison / time inc.
Find Gold Amt % / time inc.
Find Magic % / time inc.
% / time inc.
Stamina / time inc.
Damage to Demons % / time inc.
Damage to Undead % / time inc.
To Hit Demons % / time inc.
To Hit Undead % / time inc.
% chance of Crushing Blow / time inc.
+% chance of Open Wounds / time inc.
Kick Damage / time inc.
+% chance of Deadly Strike / time inc.
+% chance of finding Gems / time inc.
Negates % of Enemy Cold Resistance
Negates % of Enemy Fire Resistance
Negates % of Enemy Lightning Resistance
Negates % of Enemy Poison Resistance
Damage vs. specific Monster Type
Damage % vs. specific Monster Type
To Hit vs. specific Monster Type
To Hit % vs. specific Monster Type
AC vs. specific Monster Type
AC% vs. specific Monster Type
Indestructible
Charged Skill








additional xp gain
life gained after each kill
reduces vendor prices
slain monsters' corpses can't be used
attack% vs. monster type
damage% vs. monster type
Proc Skill on killing something
Proc Skill on getting killed
Proc Skill on level up
Bonus to random skill
only use on monprop






?>