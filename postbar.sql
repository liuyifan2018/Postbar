/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : postbar

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 22/02/2019 19:01:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for coll
-- ----------------------------
DROP TABLE IF EXISTS `coll`;
CREATE TABLE `coll`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nid` int(10) NOT NULL,
  `coll` int(1) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of coll
-- ----------------------------
INSERT INTO `coll` VALUES (1, '915458370', 1, 1, 1550284171);

-- ----------------------------
-- Table structure for content
-- ----------------------------
DROP TABLE IF EXISTS `content`;
CREATE TABLE `content`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nid` int(10) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` int(10) NOT NULL,
  `is_show` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of content
-- ----------------------------
INSERT INTO `content` VALUES (1, 'www', 1, '915458370', 1550286444, 1);

-- ----------------------------
-- Table structure for friend
-- ----------------------------
DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `friend` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_fd` int(1) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of friend
-- ----------------------------
INSERT INTO `friend` VALUES (3, '87535264', '915458370', 1, 1550822824);
INSERT INTO `friend` VALUES (4, '29161241', '915458370', 1, 1550822877);

-- ----------------------------
-- Table structure for good
-- ----------------------------
DROP TABLE IF EXISTS `good`;
CREATE TABLE `good`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nid` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good
-- ----------------------------
INSERT INTO `good` VALUES (1, '915458370', 1);

-- ----------------------------
-- Table structure for note
-- ----------------------------
DROP TABLE IF EXISTS `note`;
CREATE TABLE `note`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` int(10) NOT NULL,
  `tion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_show` int(1) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of note
-- ----------------------------
INSERT INTO `note` VALUES (1, '测试', '测试', 1550218133, '开心乐园', '915458370', 1, 108);
INSERT INTO `note` VALUES (2, '测试', '测试', 1550218525, '开心乐园', '915458370', 1, 0);
INSERT INTO `note` VALUES (3, 'dwda', 'dwadaw', 1550284171, '开心乐园', '915458370', 1, 0);

-- ----------------------------
-- Table structure for signed
-- ----------------------------
DROP TABLE IF EXISTS `signed`;
CREATE TABLE `signed`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of signed
-- ----------------------------
INSERT INTO `signed` VALUES (1, '915458370', 1550482395);
INSERT INTO `signed` VALUES (2, '915458370', 1550543092);
INSERT INTO `signed` VALUES (3, '915458370', 1550632299);
INSERT INTO `signed` VALUES (4, '915458370', 1550818911);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(48) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` int(10) NOT NULL,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `insider` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `money` int(10) NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `state` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '915458370', '1', '915458370@qq.com', 1550213609, '13103703967', '皇帝', 'https://thirdqq.qlogo.cn/qqapp/101235792/2339E6B59BCDC3699DAB18BEFBC1306D/100', '至尊会员', 25, '郑州', 'you can\'t keep me, I\'m the wind', '在线');
INSERT INTO `user` VALUES (2, '87535264', '123', '87535264@qq.com', 1550213609, '13103703967', '二少', 'https://thirdqq.qlogo.cn/qqapp/101235792/2339E6B59BCDC3699DAB18BEFBC1306D/100', '钻石会员', 0, '郑州', '', '离线');
INSERT INTO `user` VALUES (3, '29161241', '123', '29161241@qq.com', 1550214318, '13103703967', '风遇你', 'https://thirdqq.qlogo.cn/qqapp/101235792/2339E6B59BCDC3699DAB18BEFBC1306D/100', '黄金会员', 0, '郑州', '', '离线');
INSERT INTO `user` VALUES (4, '81553819', '11', '81553819@qq.com', 1550214859, '13103703967', '神明', 'https://thirdqq.qlogo.cn/qqapp/101235792/2339E6B59BCDC3699DAB18BEFBC1306D/100', '普通会员', 0, '郑州', '', '离线');

SET FOREIGN_KEY_CHECKS = 1;
