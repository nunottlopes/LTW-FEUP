<pre>
<?php
// TEST Channel
// 
// 

require_once 'entities/channel.php';

// Channel::readAll
echo json_encode(Channel::readAll(), JSON_PRETTY_PRINT);

// Channel::read

?>
</pre>