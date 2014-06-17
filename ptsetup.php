<?php // ptsetup.php
include_once 'ptfunctions.php';

createTable('ptmembers', 'user VARCHAR(16), pass VARCHAR(16),
	 	    INDEX(user(6))');

createTable('ptmessages', 
		   'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	 	    auth VARCHAR(16), recip VARCHAR(16), pm CHAR(1),
	 	    time INT UNSIGNED, message VARCHAR(4096),
		    INDEX(auth(6)), INDEX(recip(6))');

createTable('ptfriends', 'user VARCHAR(16), friend VARCHAR(16),
	 	    INDEX(user(6)), INDEX(friend(6))');

createTable('ptprofiles', 'user VARCHAR(16), text VARCHAR(4096),
	 	    INDEX(user(6))');

createTable('ptappointments', 'tutorPID VARCHAR(16), starttime TIME, 
                    endtime TIME, user VARCHAR(16), date DATE,
                    subject VARCHAR(16), 
                    INDEX(tutorPID(6)), INDEX(user(6))');
?>
