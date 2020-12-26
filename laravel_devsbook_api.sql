-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.11-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para laravel_devsbook_api
CREATE DATABASE IF NOT EXISTS `laravel_devsbook_api` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `laravel_devsbook_api`;

-- Copiando estrutura para tabela laravel_devsbook_api.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.migrations: ~0 rows (aproximadamente)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2020_12_07_202954_create_all_tables', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Copiando estrutura para tabela laravel_devsbook_api.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.posts: ~6 rows (aproximadamente)
DELETE FROM `posts`;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` (`id`, `id_user`, `type`, `body`, `created_at`) VALUES
	(1, 19, 'text', 'Testando', '2020-12-11 19:34:20'),
	(3, 19, 'photo', '1.jpg', '2020-12-11 19:42:17'),
	(4, 19, 'text', 'Testando 2', '2020-12-11 19:48:40'),
	(5, 19, 'text', 'Testando 3', '2020-12-11 19:48:49'),
	(6, 19, 'text', 'text com body', '2020-12-11 19:58:21'),
	(7, 19, 'text', 'text', '2020-12-11 19:58:48');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

-- Copiando estrutura para tabela laravel_devsbook_api.post_comments
CREATE TABLE IF NOT EXISTS `post_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.post_comments: ~2 rows (aproximadamente)
DELETE FROM `post_comments`;
/*!40000 ALTER TABLE `post_comments` DISABLE KEYS */;
INSERT INTO `post_comments` (`id`, `id_post`, `id_user`, `body`, `created_at`) VALUES
	(1, 1, 19, 'Comentario enviado pela api', '2020-12-12 00:54:39'),
	(2, 1, 19, 'segundo comentario', '2020-12-12 00:55:08'),
	(3, 1, 19, 'comentario no post 1', '2020-12-13 22:40:09');
/*!40000 ALTER TABLE `post_comments` ENABLE KEYS */;

-- Copiando estrutura para tabela laravel_devsbook_api.post_likes
CREATE TABLE IF NOT EXISTS `post_likes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.post_likes: ~0 rows (aproximadamente)
DELETE FROM `post_likes`;
/*!40000 ALTER TABLE `post_likes` DISABLE KEYS */;
INSERT INTO `post_likes` (`id`, `id_post`, `id_user`, `created_at`) VALUES
	(2, 1, 19, '2020-12-13 22:39:51');
/*!40000 ALTER TABLE `post_likes` ENABLE KEYS */;

-- Copiando estrutura para tabela laravel_devsbook_api.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.jpg',
  `cover` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cover.jpg',
  `token` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.users: ~1 rows (aproximadamente)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`, `name`, `birthdate`, `city`, `work`, `avatar`, `cover`, `token`) VALUES
	(19, 'suporte@b7web.com.br', '$2y$10$642hvshD56QPRrjgogPBpOikeiJ1PcMpLGt343809KphnLqCMu/76', 'Bonieky Lacerda', '1910-01-12', 'Rio de janeiro', 'b7web', 'default.jpg', 'cover.jpg', NULL),
	(20, 'luizpsmoreira@gmail.com', '$2y$10$arw5I3Bq2h2g23xLz6RinO8n.loInSIPgPYAWkkqkBzz.1TMm2x1W', 'Luiz Pedro', '1993-07-03', NULL, NULL, 'default.jpg', 'cover.jpg', NULL),
	(21, 'teste@gmail.com', '$2y$10$UMvq3H64hodJH.Cglov30utoEB3bPNzL3y/RSCNPTUKH4Bp63ZGRG', 'Usuario de teste', '1990-09-09', NULL, NULL, 'default.jpg', 'cover.jpg', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Copiando estrutura para tabela laravel_devsbook_api.user_relations
CREATE TABLE IF NOT EXISTS `user_relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela laravel_devsbook_api.user_relations: ~0 rows (aproximadamente)
DELETE FROM `user_relations`;
/*!40000 ALTER TABLE `user_relations` DISABLE KEYS */;
INSERT INTO `user_relations` (`id`, `user_from`, `user_to`) VALUES
	(2, 19, 20);
/*!40000 ALTER TABLE `user_relations` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
