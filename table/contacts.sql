-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 04, 2024 at 02:24 PM
-- Server version: 8.0.36-cll-lve
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stonesti_pandb_imports`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `message` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `created_at`, `updated_at`, `subject`) VALUES
(4, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'ekuszdghcjsxkvukcbdkv,c cxngukbdvj,xmgc s,jxvhgdkjvbkdgdjm', '2022-07-19 06:15:18', '2022-07-19 06:15:18', NULL),
(5, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'ekuszdghcjsxkvukcbdkv,c cxngukbdvj,xmgc s,jxvhgdkjvbkdgdjm', '2022-07-19 06:19:30', '2022-07-19 06:19:30', NULL),
(6, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'skjdghkqe,kj,', '2022-07-19 06:22:17', '2022-07-19 06:22:17', NULL),
(7, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'Hello,\r\nthis is an appreciation post\r\nYou have done an splendid job here onto this project', '2022-07-19 06:24:28', '2022-07-19 06:24:28', NULL),
(8, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'drrlsgdjklkdt', '2022-07-19 06:30:13', '2022-07-19 06:30:13', NULL),
(9, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'dldr/rk.hcvjhidk', '2022-07-19 06:30:43', '2022-07-19 06:30:43', NULL),
(10, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'Hello,\r\nThis Is an appreciation email \r\nYou have done an splendid Job in developing pandbimports.com', '2022-07-19 06:35:12', '2022-07-19 06:35:12', NULL),
(11, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'Hello Honey,\r\nThis is an appreciation E-mail \r\nYou have done an splendid job in Developing pandbimports.com', '2022-07-19 06:37:53', '2022-07-19 06:37:53', NULL),
(12, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'skghvwe,sdubcc,xjg,ku,vdcvxn,ghcmjdvkkgjcxvjdxc nbjvncgvjb g,mvnmbdvjg n', '2022-07-19 06:54:15', '2022-07-19 06:54:15', 'Inquiry about Moonstone'),
(13, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'rjekhsgvjrbndsxnijsudkjkc7d.t,ygkhjkevusuldx.ji.ukdllkj', '2022-07-19 11:22:53', '2022-07-19 11:22:53', 'zdkjxnbdjzklxgjb'),
(14, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'kuetjsfchgbiugdjkx', '2022-07-19 11:24:18', '2022-07-19 11:24:18', 'dsgdkjs'),
(15, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'Name', '2022-07-19 11:25:19', '2022-07-19 11:25:19', 'Inquiry about Moonstone test dynamic'),
(16, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'skjvcn', '2022-07-19 11:26:09', '2022-07-19 11:26:09', 'sjkdgnb, m'),
(17, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'PRE', '2022-07-19 11:26:42', '2022-07-19 11:26:42', 'PRE'),
(18, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'PRE', '2022-07-19 11:26:42', '2022-07-19 11:26:42', 'PRE'),
(19, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', '\\n New line', '2022-07-19 11:27:23', '2022-07-19 11:27:23', '\\n'),
(20, 'Honey Agarwal', 'honeyagarwal1221@gmail.com', '07742299986', 'jvhxkjc', '2022-07-19 11:28:21', '2022-07-19 11:28:21', 'xjkvc'),
(21, 'Vishal Singh', 'vishalsinghbanna2013@gmail.com', '07339847767', 'test', '2023-09-25 13:20:53', '2023-09-25 13:20:53', 'test'),
(22, 'Siobhan Muller', 'muller.siobhan@gmail.com', '052 780 15 72', 'I’ve been working with freelancers for over nine years now. \r\n\r\nOne of the biggest things I want businesses to know about working with freelancers is how much time and money they can save by hiring freelancers for projects. \r\n\r\nWhether you’re a multi-level corporation or a small start-up, chances are, you could benefit from using freelance work. \r\n\r\nThe details here: https://saloof.com/how', '2024-01-28 13:27:20', '2024-01-28 13:27:20', 'How Using Freelancers Save You Time & Money in 2024'),
(23, 'Gene McGowen', 'gene.mcgowen@msn.com', '53-72-23-65', 'Did you know that it costs McDonalds $1.91 in advertising to get you into the drive through...?\r\n\r\nAnd when they sell you a burger for $2.09... they ONLY make $0.18... :(\r\n\r\nBut... when they upsell you fries and a coke for $1.77 more... they make (and more importantly keep $1.32 profit)...  \r\n\r\nYes, 8 times the profit of the initial sale!\r\n\r\nPretty cool, don\'t you think? \r\n\r\nBut... what does that have to do with you?\r\n\r\nWell, If you\'re like most people who sell stuff online...\r\n\r\nYou setup a website, and you started selling your product...\r\n\r\nBut just like McDonalds... even if people are buying it...\r\n\r\nAfter your advertising costs, you\'re probably not left with enough to even cover your hosting bills. :(\r\n\r\nAnd that\'s when you realize... that if you want to make it online, you can\'t sell from a flat website...\r\n\r\nYou need to do what McDonalds did, and setup a sales funnel... \r\n\r\nBut instead of having people go through a drive-in window...\r\n\r\nOnline they go through a \"capture page\" where you can gather their contact information and follow up with them through email...\r\n\r\nThen instead of selling them a burger, you have a sales page created to sell your initial product...\r\n\r\nAnd while you\'re probably not going to offer your customers fries and a coke...\r\n\r\nYou could upsell them on your other products and services.\r\n\r\nThis is what we call a \"Sales Funnel\"\r\n\r\nWhere website visitors can come in the top...\r\n\r\nAnd cash comes out the bottom...\r\n\r\nYou have seen it work at McDonald\'s, you have seen it work on Amazon... and you KNOW it will work for you.\r\n\r\nSo, you decide to create your first sales funnel...\r\n\r\nBut after weeks of trial and error...\r\n\r\nYou ask yourself... WHY did it have to be so hard? \r\n\r\nAll you wanted to do was to sell your stuff online. \r\n\r\nBut instead you spent weeks (maybe months) playing with web builders, hiring designers and programmers BFEORE you ever made your first penny online. \r\n\r\nAnd to us... well, that seemed kinda backwards. \r\n\r\nShouldn\'t there be an easy way to create high converting marketing funnels, without having to hire an entire staff?\r\n\r\nWe thought so, and that\'s why we created ClickFunnels - a simple way to easily create sales funnels that convert!\r\n\r\nWant to see a quick demo to see how it works?  \r\n\r\nhttps://bit.ly/vipfunnels\r\n\r\nAfter you watch the video, you can get a FREE 2 week trial account here:\r\n\r\nhttps://bit.ly/vipfunnels\r\n\r\nCheck out the video, and let me know what you think. \r\n\r\nThanks,\r\n\r\nP.S. - to be successful online, there are 2 essential tools that you HAVE to have... an email autoresponder, and Clickfunnels. Go see why ClickFunnels is awesome, and then get your free account here:\r\n\r\nhttps://bit.ly/vipfunnels', '2024-01-31 15:04:29', '2024-01-31 15:04:29', 'your Sales Funnel...?'),
(24, 'Gilberto Wrenfordsley', 'wrenfordsley.gilberto@outlook.com', '22-91-11-02', 'New FREE book to reveals evergreen strategy and long-term stuff that you can use to build a list of buyers and followers in record time... \r\n\r\nNo matter what platform you use to drive traffic, including...Google, YouTube, Facebook, Instagram, Podcasts and others.\r\n\r\nFor more details: https://bit.ly/toptrafficsecrets', '2024-02-06 14:47:25', '2024-02-06 14:47:25', 'How To Fill Your Website With Your Dream Customers?'),
(25, 'Gary Stephen', 'stephen.gary@gmail.com', '0491 83 74 29', 'New FREE book to reveals evergreen strategy and long-term stuff that you can use to build a list of buyers and followers in record time... \r\n\r\nNo matter what platform you use to drive traffic, including...Google, YouTube, Facebook, Instagram, Podcasts and others.\r\n\r\nFor more details: https://bit.ly/toptrafficsecrets', '2024-02-06 23:30:49', '2024-02-06 23:30:49', 'How To Fill Your Website With Your Dream Customers?'),
(26, 'pooja', 'poojagoutam188@gmail.com', '7850834284', 'jewelry buy', '2024-02-27 08:59:36', '2024-02-27 08:59:36', 'buy'),
(27, 'Danielle Simpson', 'simpsondanielle800@gmail.com', '04.94.35.57.72', 'Hi,\r\n\r\nWe\'d like to introduce to you our explainer video service, which we feel can benefit your site bautlr.com.\r\n\r\nCheck out some of our existing videos here:\r\nhttps://www.youtube.com/watch?v=bWz-ELfJVEI\r\nhttps://www.youtube.com/watch?v=Y46aNG-Y3rM\r\nhttps://www.youtube.com/watch?v=hJCFX1AjHKk\r\n\r\nOur prices start from as little as $195 and include a professional script and voice-over.\r\n\r\nIf this is something you would like to discuss further, don\'t hesitate to reply.\r\n\r\nKind Regards,\r\nDanielle\r\n\r\nIf you are not interested, unsubscribe here: https://removeme.click/unsubscribe.php?d=bautlr.com', '2024-02-28 18:25:07', '2024-02-28 18:25:07', 'Explainer Video?'),
(28, 'Libby Evans', 'libbyevans461@gmail.com', '413 35 301', 'Hi there,\r\n\r\nWe run an Instagram growth service, which increases your number of followers safely and practically. \r\n\r\nWe aim to gain you 300-1000+ real human followers per month, with all actions safe as they are made manually (no bots).\r\n\r\nThe price is just $60 (USD) per month, and we can start immediately.\r\n\r\nLet me know if you wish to see some of our previous work.\r\n\r\nKind Regards,\r\nLibby', '2024-02-28 19:30:21', '2024-02-28 19:30:21', 'Get Noticed on Instagram: Gain 300-1000 New Followers Each Month'),
(29, 'Amelia Brown', 'ameliabrown0325@gmail.com', '0393-8263847', 'Hi there,\r\n\r\nWe run a YouTube growth service, which increases your number of subscribers safely and practically. \r\n\r\nWe aim to gain you 700-1500+ real human subscribers per month, with all actions safe as they are made manually (no bots).\r\n\r\nThe price is just $60 (USD) per month, and we can start immediately.\r\n\r\nLet me know if you wish to see some of our previous work.\r\n\r\nKind Regards,\r\nAmelia', '2024-02-29 11:13:27', '2024-02-29 11:13:27', 'YouTube Promotion: Grow your subscribers by 700-1500 each month'),
(30, 'Collin Whitham', 'whitham.collin@outlook.com', 'Jrpuzhhtjfwipr', 'So I just pre-ordered my FREE copy of Russell Brunson’s\r\nnew book, “Traffic Secrets.”\r\n\r\nAll I had to do was pay a tiny shipping charge. That’s it. \r\n\r\nHere’s the link to get your copy: \r\n\r\nPre-order My Copy of Traffic Secrets: https://bit.ly/toptrafficsecrets\r\n\r\nPersonally, I’ve been waiting for this book because I heard through the grapevine that it’s like no other ‘traffic’ book out there.\r\n\r\nBecause it’s all evergreen strategy and long-term stuff that you can \r\nuse to build a list of buyers and followers in record time... \r\n\r\nNo matter what platform you use to drive traffic, including...\r\n\r\nGoogle, YouTube, Facebook, Instagram, Podcasts and others.\r\n\r\nRussell shares the same strategies he used to take Clickfunnels\r\nfrom zero to over $100,000,000 in revenue in just 3 years.\r\n\r\nAnd you get the same traffic formulas, scripts, and blueprints he shares with his Inner Circle, ClickFunnels Collective students, and Two Comma Club members. \r\n\r\nPre-order My Copy of Traffic Secrets now: https://bit.ly/toptrafficsecrets\r\n\r\nRemember, the book is FREE + a small shipping fee.\r\n\r\nTalk soon!\r\n\r\nP.S. If you want the audio version, you can also pre-order that\r\nas well!\r\n\r\nPre-order My Copy of Traffic Secrets: https://bit.ly/toptrafficsecrets', '2024-02-29 18:36:10', '2024-02-29 18:36:10', 'I just pre-ordered my copy of “Traffic Secrets!” ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
