<?php

include('../LOM/O.php');
$O = new O('<attributes>
<attribute>
<name>strength</name>
<used>5</used>
<current>20</current>
<maximum>25</maximum>
</attribute>
<attribute>
<name>wisdom</name>
<used>5</used>
<current>32</current>
<maximum>34</maximum>
</attribute>
<attribute>
<name>intelligence</name>
<used>15</used>
<current>20</current>
<maximum>22</maximum>
</attribute>
<attribute>
<name>caring</name>
<used>2</used>
<current>8</current>
<maximum>10</maximum>
</attribute>
<attribute>
<name>will</name>
<used>16</used>
<current>33</current>
<maximum>57</maximum>
</attribute>
<attribute>
<name>dance</name>
<used>0</used>
<current>38</current>
<maximum>38</maximum>
</attribute>
</attributes>');
$attributes = $O->_('attribute');
/*print('<table border="0" cellpadding="4" cellspacing="0">
<thead>
<tr>
<th scope="col" style="text-align: left;">attribute</th><th scope="col">bar graph</th><th scope="col">used</th><th scope="col">current</th><th scope="col">maximum</th>
</tr>
</thead>
<tbody>
');*/
print('<table border="0" cellpadding="4" cellspacing="0">
<thead>
<tr>
<th scope="col" style="text-align: left;">attribute</th><th scope="col">bar graph</th><th scope="col"><abbr title="used">U</abbr>/<abbr title="current">C</abbr>/<abbr title="maximum">M</abbr></th>
</tr>
</thead>
<tbody>
');
foreach($attributes as $attribute) {
	/*print('<tr>
<th scope="row" style="text-align: left;">' . $O->_('name', $attribute) . '</th><td><div style="height: 16px; width: ' . $O->_('maximum', $attribute) . '; background: green;"><div style="height: 12px; width: ' . $O->_('current', $attribute) . '; position: relative; top: 2px; background: yellow;"><div style="height: 8px; width: ' . $O->_('used', $attribute) . '; position: relative; top: 2px; background: grey;"></div></div></div></td><td style="text-align: right;">' . $O->_('used', $attribute) . '</td><td style="text-align: right;">' . $O->_('current', $attribute) . '</td><td style="text-align: right;">' . $O->_('maximum', $attribute) . '</td>
</tr>
');*/
	print('<tr>
<th scope="row" style="text-align: left;">' . $O->_('name', $attribute) . '</th><td><div style="height: 16px; width: ' . $O->_('maximum', $attribute) . '; background: green;"><div style="height: 12px; width: ' . $O->_('current', $attribute) . '; position: relative; top: 2px; background: yellow;"><div style="height: 8px; width: ' . $O->_('used', $attribute) . '; position: relative; top: 2px; background: grey;"></div></div></div></td><td style="text-align: right;">' . $O->_('used', $attribute) . '/' . $O->_('current', $attribute) . '/' . $O->_('maximum', $attribute) . '</td>
</tr>
');
}
print('</tbody>
</table>
');

?>