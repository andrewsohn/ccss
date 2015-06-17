DROP DATABASE IF EXISTS  bearfamily;
CREATE DATABASE IF NOT EXISTS `bearfamily` DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci`;

use bearfamily;

CREATE TABLE IF NOT EXISTS `AdminMember` (
  `mb_no` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `mb_password` varchar(255) NOT NULL DEFAULT '',
  `mb_name` varchar(255) NOT NULL DEFAULT '',
  `mb_nick` varchar(255) NOT NULL DEFAULT '',
  `mb_email` varchar(255) NOT NULL DEFAULT '',
  `mb_level` tinyint(4) NOT NULL DEFAULT '0',
  `mb_sex` char(1) NOT NULL DEFAULT '',
  `mb_birth` varchar(255) NOT NULL DEFAULT '',
  `mb_tel` varchar(255) NOT NULL DEFAULT '',
  `mb_hp` varchar(255) NOT NULL DEFAULT '',
  `mb_certify` varchar(20) NOT NULL DEFAULT '',
  `mb_adult` tinyint(4) NOT NULL DEFAULT '0',
  `mb_dupinfo` varchar(255) NOT NULL DEFAULT '',
  `mb_recommend` varchar(255) NOT NULL DEFAULT '',
  `mb_today_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_login_ip` varchar(255) NOT NULL DEFAULT '',
  `mb_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_ip` varchar(255) NOT NULL DEFAULT '',
  `mb_leave_date` varchar(8) NOT NULL DEFAULT '',
  `mb_memo` text NOT NULL,
  `mb_open` tinyint(4) NOT NULL DEFAULT '0',
  `mb_open_date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB COMMENT='관리자';


-- 테이블의 인덱스 `AdminMember`
--
ALTER TABLE `AdminMember`
 ADD UNIQUE KEY `mb_id` (`mb_id`), ADD KEY `mb_today_login` (`mb_today_login`), ADD KEY `mb_datetime` (`mb_datetime`);

--
-- 테이블의 덤프 데이터 `AdminMember`
--

INSERT INTO `AdminMember` (`mb_no`, `mb_id`, `mb_password`, `mb_name`, `mb_nick`, `mb_email`, `mb_level`, `mb_sex`, `mb_birth`, `mb_tel`, `mb_hp`, `mb_certify`, `mb_adult`, `mb_dupinfo`, `mb_recommend`, `mb_today_login`, `mb_login_ip`, `mb_datetime`, `mb_ip`, `mb_leave_date`, `mb_memo`, `mb_open`, `mb_open_date`) VALUES
(1, 'admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', '최고관리자', '최고관리자', 'andrewsohn@hivelab.co.kr', 10, '', '', '', '', '', 0, '', '', '2015-01-14 16:13:46', '192.168.0.1', '2014-11-14 11:28:17', '::1', '', '', 1, '0000-00-00'),
(2, 'andrew', '*A4B6157319038724E3560894F7F932C8886EBFCF', '손진광', '손진광', 'jkson21@naver.com', 2, '', '', '', '', '', 0, '', '', '2014-11-14 14:00:15', '::1', '2014-11-14 14:00:15', '::1', '', '', 1, '2014-11-14');

-- --------------------------------------------------------

--
-- 테이블 구조 `AdminMenu`
--

CREATE TABLE IF NOT EXISTS `AdminMenu` (
  `am_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '아이디',
  `am_name` varchar(255) NOT NULL COMMENT '메뉴명',
  `am_code` varchar(50) NOT NULL COMMENT '메뉴 코드',
  `am_order` tinyint(4) DEFAULT NULL COMMENT '메뉴 순서',
  `am_menu` tinyint(4) NOT NULL COMMENT '대메뉴 순서',
  `am_display` tinyint(4) NOT NULL DEFAULT '1' COMMENT '관리자 메뉴 노출 여부'
) ENGINE=InnoDB COMMENT='관리자 메뉴 테이블';


INSERT INTO `AdminMenu` (`am_id`, `am_name`, `am_code`, `am_order`, `am_menu`, `am_display`) VALUES
(13, '이벤트 티저 관리', 'EventTeaser', 1, 1, 1),
(14, '사전예약 경품 관리', 'PreReserveHistory', 1, 3, 1),
(15, '이벤트참여자관리', 'EventApplicant', 1, 2, 1),
(16, '1차 티저 참여자', 'Teaser1Applicant', 2, 2, 1),
(17, '2차 티저 참여자', 'Teaser2Applicant', 3, 2, 1),
(18, 'Analytics', 'Analytics', 1, 5, 1),
(19, '사전예약참여자관리', 'PreReserveApplicant', 1, 4, 1);


CREATE TABLE IF NOT EXISTS `Menu` (
  `me_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `me_code` varchar(255) NOT NULL DEFAULT '',
  `me_name` varchar(255) NOT NULL DEFAULT '',
  `me_target` varchar(255) NOT NULL DEFAULT '',
  `me_order` int(11) NOT NULL DEFAULT '0',
  `me_use` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB COMMENT='메뉴';


INSERT INTO `Menu` (`me_id`, `me_code`, `me_name`, `me_target`, `me_order`, `me_use`) VALUES
(4, '10', '테스트', 'self', 0, 1);


CREATE TABLE Codes
(
	gid                  SMALLINT UNSIGNED NOT NULL COMMENT '그룹ID',
	id                   SMALLINT UNSIGNED NOT NULL COMMENT '코드ID', 
	name                 VARCHAR(30) NULL COMMENT '그룹명',
	visible              ENUM('N', 'Y') NOT NULL DEFAULT 'Y' COMMENT '활성/비활성'
) ENGINE=InnoDB COMMENT='코드';



ALTER TABLE Codes
ADD PRIMARY KEY (gid,id);



CREATE TABLE Events
(
	idx                  MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '이벤트 인덱스',
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
	dpLocation           SMALLINT UNSIGNED NULL COMMENT '이벤트 표시 위치',
	title                VARCHAR(60) NOT NULL COMMENT '이벤트명',
	videoUrl             VARCHAR(500) NULL COMMENT '동영상 URL',
	content              TEXT NULL COMMENT '내용',
	registDt             DATETIME NULL COMMENT '등록일',
	 PRIMARY KEY (idx)
) ENGINE=InnoDB COMMENT='이벤트';


CREATE INDEX idx_events_01 ON Events
(
	status
);



CREATE TABLE EventsNotices
(
	idx                  VARCHAR(32) NOT NULL COMMENT '이벤트 게시글 인덱스',
	eventIdx             MEDIUMINT UNSIGNED NOT NULL COMMENT '이벤트 인덱스',
	userId 				 VARCHAR(25) NULL COMMENT '회원 아이디',
	userType             SMALLINT UNSIGNED NOT NULL COMMENT '회원가입 종류',	
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
	type                 SMALLINT UNSIGNED NOT NULL COMMENT '게시글 타입(FB,TWT...)',
	subject              VARCHAR(100) COMMENT '제목',
	content              TEXT NULL COMMENT '내용',
	refIdx               VARCHAR(32) NULL COMMENT '참조 게시글 인덱스',	
	hits                 MEDIUMINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '조회수',
	photoType		     SMALLINT UNSIGNED NOT NULL COMMENT '업로드사진 파일종류(jpg,png...)',	
	regIP				 VARCHAR(39) NOT NULL COMMENT '등록 IPv4 or IPv6',
	registDt             DATETIME NULL COMMENT '등록일'
) ENGINE=InnoDB COMMENT='이벤트 게시물';



ALTER TABLE EventsNotices
ADD PRIMARY KEY (idx);



CREATE INDEX idx_events_notices_01 ON EventsNotices
(
	status
);



CREATE TABLE Users
(
	id                   VARCHAR(25) NULL COMMENT '회원 아이디',
	type                 SMALLINT UNSIGNED NOT NULL COMMENT '회원가입 종류',
	name                 VARCHAR(50) NULL COMMENT '사용자명',
	photoUrl             VARCHAR(350) NULL COMMENT '사진 URL',
	visible              ENUM('N', 'Y') NOT NULL DEFAULT 'Y' COMMENT '활성/비활성',
	registDt             DATETIME NULL COMMENT '등록일'
) ENGINE=InnoDB COMMENT='회원';



ALTER TABLE Users
ADD PRIMARY KEY (id, type);



CREATE INDEX idx_users_01 ON Users
(
	visible
);



CREATE UNIQUE INDEX uk_users_01 ON Users
(
	id,
	type
);


ALTER TABLE EventsNotices
ADD FOREIGN KEY rf_events_events_notices (eventIdx) REFERENCES Events (idx);


ALTER TABLE EventsNotices
ADD FOREIGN KEY rf_users_events_notices (userId, userType) REFERENCES Users (id, type);

INSERT INTO Codes(gid, id, name) VALUES (0, 1, '게시글 상태');
INSERT INTO Codes(gid, id, name) VALUES (1, 0, '숨기기');
INSERT INTO Codes(gid, id, name) VALUES (1, 1, '보이기');

INSERT INTO Codes(gid, id, name) VALUES (0, 2, '이벤트 상태');
INSERT INTO Codes(gid, id, name) VALUES (2, 0, '숨기기');
INSERT INTO Codes(gid, id, name) VALUES (2, 1, '보이기');
INSERT INTO Codes(gid, id, name) VALUES (2, 2, '대기');

INSERT INTO Codes(gid, id, name) VALUES (0, 3, 'SNS 종류');
INSERT INTO Codes(gid, id, name) VALUES (3, 1, 'Facebook');
INSERT INTO Codes(gid, id, name) VALUES (3, 2, 'Twitter');

INSERT INTO Codes(gid, id, name) VALUES (0, 4, '모바일 종류');
INSERT INTO Codes(gid, id, name) VALUES (4, 1, '안드로이드');
INSERT INTO Codes(gid, id, name) VALUES (4, 2, '아이폰');

INSERT INTO Codes(gid, id, name) VALUES (0, 5, '상태');
INSERT INTO Codes(gid, id, name) VALUES (5, 0, '숨기기');
INSERT INTO Codes(gid, id, name) VALUES (5, 1, '보이기');
INSERT INTO Codes(gid, id, name) VALUES (5, 9, '삭제');

INSERT INTO Codes(gid, id, name) VALUES (0, 10, '캐릭터 종류');
INSERT INTO Codes(gid, id, name) VALUES (10, 1, '캐릭터1');
INSERT INTO Codes(gid, id, name) VALUES (10, 2, '캐릭터2');
INSERT INTO Codes(gid, id, name) VALUES (10, 3, '캐릭터3');
INSERT INTO Codes(gid, id, name) VALUES (10, 4, '캐릭터4');
INSERT INTO Codes(gid, id, name) VALUES (10, 5, '캐릭터5');
INSERT INTO Codes(gid, id, name) VALUES (10, 6, '캐릭터6');

INSERT INTO Codes(gid, id, name) VALUES (0, 20, '게시물 종류');
INSERT INTO Codes(gid, id, name) VALUES (20, 0, 'jpg');
INSERT INTO Codes(gid, id, name) VALUES (20, 1, 'png');
INSERT INTO Codes(gid, id, name) VALUES (20, 2, 'jpeg');

INSERT INTO Codes(gid, id, name) VALUES (0, 30, '성별');
INSERT INTO Codes(gid, id, name) VALUES (30, 1, '남성');
INSERT INTO Codes(gid, id, name) VALUES (30, 2, '여성');

INSERT INTO Codes(gid, id, name) VALUES (0, 40, '연령대');
INSERT INTO Codes(gid, id, name) VALUES (40, 10, '10대');
INSERT INTO Codes(gid, id, name) VALUES (40, 20, '20대');
INSERT INTO Codes(gid, id, name) VALUES (40, 30, '30대');
INSERT INTO Codes(gid, id, name) VALUES (40, 40, '40대');
INSERT INTO Codes(gid, id, name) VALUES (40, 50, '50대 이상');


CREATE TABLE Goods
(
	idx                  SMALLINT UNSIGNED NOT NULL COMMENT '상품 인덱스', 
	name				 VARCHAR(100) NOT NULL COMMENT '상품명',
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태'
) ENGINE=InnoDB COMMENT='상품';

ALTER TABLE Goods
ADD PRIMARY KEY (idx);

ALTER TABLE `Goods`
MODIFY `idx` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '상품 인덱스';


CREATE TABLE PromotionGoods
(
	idx                  MEDIUMINT UNSIGNED NOT NULL COMMENT '경품 인덱스', 
	goodsIdx             SMALLINT UNSIGNED NOT NULL COMMENT '상품 인덱스', 	
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
	winningRate			 SMALLINT UNSIGNED NOT NULL COMMENT '당청율',
	limitDailyWinGoods	 SMALLINT UNSIGNED NOT NULL COMMENT '일일 당첨한도',
	amount	 SMALLINT UNSIGNED NOT NULL COMMENT '경품수량'
) ENGINE=InnoDB COMMENT='경품';

ALTER TABLE PromotionGoods
ADD PRIMARY KEY (idx);

ALTER TABLE `PromotionGoods`
MODIFY `idx` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT '경품 인덱스';

ALTER TABLE PromotionGoods
ADD FOREIGN KEY rf_goods_promotion_goods (goodsIdx) REFERENCES Goods (idx);

CREATE TABLE DailyPrizeWinners
(
	dt                   DATE NOT NULL DEFAULT '0000-00-00' COMMENT '당첨일',	
	prmGoodsIdx          MEDIUMINT UNSIGNED NOT NULL COMMENT '경품 인덱스',
	amount               SMALLINT UNSIGNED NOT NULL COMMENT '당첨자 수'
) ENGINE=InnoDB COMMENT='일일 당첨자 수';

ALTER TABLE DailyPrizeWinners
ADD PRIMARY KEY (dt, prmGoodsIdx);



CREATE TABLE Reservations
(
	idx					 MEDIUMINT UNSIGNED NOT NULL COMMENT '사전예약 인덱스',
	mobileNum			 VARCHAR(12) NOT NULL COMMENT '휴대폰번호', 
	userId 				 VARCHAR(25) NULL COMMENT '회원 아이디',
	userName			 VARCHAR(25) NULL COMMENT '회원 이름',
	userType             SMALLINT UNSIGNED NULL COMMENT '회원가입 종류',		
	charIdx 			 SMALLINT UNSIGNED NOT NULL COMMENT '캐릭터 종류',
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
	type                 SMALLINT UNSIGNED NOT NULL COMMENT '게시글 타입(FB,TWT...)',
	mtype                 SMALLINT UNSIGNED NOT NULL COMMENT '모바일 기기 타입(안드로이드,iOS)',
	content              TEXT NULL COMMENT '내용',
	prmGoodsIdx          MEDIUMINT UNSIGNED NULL COMMENT '경품 인덱스',   
	regIP				 VARCHAR(39) NOT NULL COMMENT '등록 IPv4 or IPv6',
	registDt             DATETIME NULL COMMENT '등록일'
) ENGINE=InnoDB COMMENT='사전예약';


ALTER TABLE Reservations
ADD PRIMARY KEY (idx);

ALTER TABLE `Reservations`
MODIFY `idx` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT '사전예약 인덱스';

CREATE UNIQUE INDEX reservations_01 ON Reservations
(
	userId,
	userType
);


ALTER TABLE Reservations
ADD FOREIGN KEY rf_users_reservations (userId, userType) REFERENCES Users (id, type);

ALTER TABLE Reservations
ADD FOREIGN KEY rf_promotion_goods_reservations (prmGoodsIdx) REFERENCES PromotionGoods (idx);

CREATE TABLE IF NOT EXISTS `Movie` (
  `idx` mediumint(8) unsigned NOT NULL COMMENT '영상 인덱스',
  `title` varchar(60) NOT NULL COMMENT '영상명',
  `url` varchar(500) NOT NULL COMMENT '영상 링크',
  `regDt` datetime NOT NULL COMMENT '영상 등록일',
  `imgName` varchar(255) NOT NULL COMMENT '이미지명',
  `imgSize` int(11) unsigned NOT NULL COMMENT '이미지 사이즈',
  `imgType` smallint(5) unsigned NOT NULL COMMENT '이미지 확장자 타입',
  `orderNum` smallint(5) unsigned NOT NULL COMMENT '영상 노출 순서'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='영상 갤러리';

ALTER TABLE `Movie`
  ADD PRIMARY KEY (`idx`);
  
ALTER TABLE `Movie`
  MODIFY `idx` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '영상 인덱스';


CREATE TABLE IF NOT EXISTS SodaPartyApplicants
(	
	mobileNum   	     VARCHAR(12) NOT NULL COMMENT '휴대폰번호',
	name 				 VARCHAR(25) NOT NULL COMMENT '성명',	
	sex              	 SMALLINT UNSIGNED NOT NULL COMMENT '성별',	
	ageBand              SMALLINT UNSIGNED NOT NULL COMMENT '연령대',	
	snsUserName			 VARCHAR(50) NULL COMMENT '페북 사용자명',
	status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
	regIP				 VARCHAR(39) NOT NULL COMMENT '등록 IPv4 or IPv6',
	registDt             DATETIME NULL COMMENT '등록일'
) ENGINE=InnoDB COMMENT='스윗소다 초청 신청';

ALTER TABLE SodaPartyApplicants ADD PRIMARY KEY (mobileNum);


SET SQL_SAFE_UPDATES = 0;

UPDATE `bearfamily`.`menu` 
SET `me_code`='sodaparty' 
, me_name = 'EVENT3. 스윗소다파티! 초대합니다'
WHERE `me_id`='7';

CREATE TABLE IF NOT EXISTS SecurityKeys
(	
	key  				VARCHAR()	
) ENGINE=InnoDB COMMENT='RSAKey';

CREATE TABLE IF NOT EXISTS `Game` (
  `idx` mediumint(8) unsigned  AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '게임 인덱스',
  `name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '게임명',
  `g_url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '구글플레이URL',
  `a_url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '앱스토어URL'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='게임';

INSERT INTO `Game` (`idx`, `name`, `g_url`, `a_url`) VALUES
(1, '캔디크러쉬소다', NULL, NULL);

CREATE TABLE IF NOT EXISTS `ExtraEvent` (
  `idx` mediumint(8) unsigned  AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '이벤트 인덱스',
  `title` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '이벤트명',
  `url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '이벤트 URL'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='이벤트';

INSERT INTO `ExtraEvent` (`idx`, `title`, `url`) VALUES
(1, '스윗 모먼트', NULL);

CREATE TABLE IF NOT EXISTS `EventVisitorCount` (
  `vc_id`		 	MEDIUMINT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '이벤트 방문자수 고유번호',
  `event_idx`       MEDIUMINT UNSIGNED NOT NULL COMMENT '이벤트 인덱스',
  `vc_cnt`  		MEDIUMINT UNSIGNED NOT NULL COMMENT '방문자 카운트'
) ENGINE=InnoDB COMMENT='이벤트 방문자수';
