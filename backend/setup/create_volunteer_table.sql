-- Create volunteer_submissions table if it doesn't exist
CREATE TABLE IF NOT EXISTS `volunteer_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `volunteer_type` varchar(20) NOT NULL,
  `availability` varchar(20) NOT NULL,
  `areas_of_interest` varchar(50) NOT NULL,
  `skills` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `motivation` text NOT NULL,
  `hear_about` varchar(20) NOT NULL,
  `newsletter` tinyint(1) DEFAULT 0,
  `terms` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
