<?php


require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Update extends Home
{
      
    public function __construct()
    {
        parent::__construct();     
        $this->upload_path = realpath(APPPATH . '../upload');
        set_time_limit(0);
    }


    public function index()
    {
        $this->v1_2to_v2();
    }


    public function v1_2to_v2()
    {

        $lines='ALTER TABLE  `users` ADD  `package_id` INT( 11 ) NOT NULL AFTER  `expired_date`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) CHARACTER SET latin1 NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT "0",
  `validity` int(11) NOT NULL,
  `is_default` enum("0","1") NOT NULL,
  `deleted` enum("0","1") CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `is_default`, `deleted`) VALUES
(1, "Trial", "2,1,8,7,9,5,3,4,6", \'{"2":"0","1":"0","8":"0","7":"0","9":"0","5":"0","3":"0","4":"0","6":"0"}\', \'{"2":"0","1":"0","8":"0","7":"0","9":"0","5":"0","3":"0","4":"0","6":"0"}\', "Trial", "7", "1", "0");


CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `deleted` enum("0","1") NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES
(1, "Crawl Website", "0"),
(2, "Crawl URL", "0"),
(3, "Search in Search Engine", "0"),
(4, "Search in Text/XML/JSON", "0"),
(5, "Search in Doc/Docx/Pdf", "0"),
(6, "Whois Search", "0"),
(7, "Email Validation Check", "0"),
(8, "Duplicate Email Filter", "0"),
(9, "Page Status Check", "0");


CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `transaction_history` ADD `package_id` INT NOT NULL AFTER `cycle_start_date` ;

CREATE
 ALGORITHM = UNDEFINED
 VIEW `view_usage_log`
 (id,module_id,user_id,usage_month,usage_year,usage_count)
 AS select * from usage_log where `usage_month`=MONTH(curdate()) and `usage_year`= YEAR(curdate()) ;
 ALTER TABLE  `payment_config` DROP  `monthly_fee`';


       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo "AES SaasS has been updated to v2.0 successfully.".$count." queries executed.";
        $this->delete_update();        
    }


    function delete_update()
    {
        unlink(APPPATH."controllers/update.php");
    }
 


}
