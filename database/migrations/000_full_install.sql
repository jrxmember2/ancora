-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 27/03/2026 às 15:56
-- Versão do servidor: 8.0.45-cll-lve
-- Versão do PHP: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `controlproposta`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradoras`
--

CREATE TABLE `administradoras` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('administradora','sindico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'administradora',
  `contact_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `administradoras`
--

INSERT INTO `administradoras` (`id`, `name`, `type`, `contact_name`, `phone`, `email`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'LF Control', 'administradora', 'Lourdes', '(27) 99668-9948', 'gerencia@lfcontrol.com.br', 1, 1, '2026-03-18 23:36:03', '2026-03-18 23:42:33'),
(2, 'Paula Cordeiro', 'sindico', 'Paula Cordeiro', '(27) 99999-1710', 'paulacordeiro.sindica@gmail.com', 1, 2, '2026-03-18 23:36:03', '2026-03-18 23:50:14'),
(3, 'Márcia Alves', 'sindico', 'Márcia Alves', '(27) 99738-1252', 'alveswanderson944@gmail.com', 1, 3, '2026-03-18 23:36:03', '2026-03-18 23:51:44'),
(4, 'Condonal', 'administradora', 'Luiz', '(27) 99225-0920', 'luiz.lins@condonal.com.br', 1, 0, '2026-03-18 23:41:39', '2026-03-18 23:41:39'),
(5, 'Marcele Miranda', 'sindico', '', '(27) 99748-4166', 'marcelealves.miranda@gmail.com', 1, 0, '2026-03-21 17:11:49', '2026-03-21 17:11:49'),
(6, 'Melissa Calzavara', 'sindico', '', '(27) 99254-8687', 'melissasindicaprof@gmail.com', 1, 0, '2026-03-23 14:39:38', '2026-03-23 14:40:48'),
(7, 'AC Administradora', 'administradora', 'Luciano', '(27) 99933-5409', 'operacional@acadm.com.br', 1, 0, '2026-03-24 18:55:44', '2026-03-24 18:55:44'),
(8, 'Harmonize Administradora', 'administradora', 'Cenir', '(27) 99980-4607', '', 1, 0, '2026-03-24 19:12:56', '2026-03-24 19:12:56'),
(9, 'Ismael', 'sindico', 'Ismael', '(27) 98819-1571', '', 1, 0, '2026-03-24 19:26:15', '2026-03-24 19:26:15'),
(10, 'Rosângela Heringer', 'sindico', 'Rosângela', '(27) 99999-0203', '', 1, 0, '2026-03-24 19:37:03', '2026-03-24 19:37:03'),
(12, 'Andressa Mendes', 'sindico', '', '(27) 99599-1531', '', 1, 0, '2026-03-24 19:46:21', '2026-03-24 19:46:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `setting_key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `app_settings`
--

INSERT INTO `app_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'ControlProposta', 'Nome do sistema', '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(2, 'app_company', 'Rebeca Medina Soluções Jurídicas', 'Nome da empresa exibido no sistema', '2026-03-18 23:36:03', '2026-03-22 12:29:16'),
(3, 'app_base_url', 'https://ancora.rebecamedina.com.br', 'URL principal do sistema', '2026-03-18 23:36:03', '2026-03-27 00:14:10'),
(4, 'followup_alert_email', 'contato@rebecamedina.com.br', 'E-mail destinatário dos alertas diários', '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(5, 'timezone', 'America/Sao_Paulo', 'Timezone padrão do sistema', '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(6, 'branding_logo_path', '/assets/uploads/branding/logo-20260322-095552-b186464b.png', 'Caminho público da logo do sistema', '2026-03-21 16:29:01', '2026-03-22 12:55:52'),
(7, 'branding_logo_height_desktop', '44', 'Altura da logo no header desktop', '2026-03-21 16:29:01', '2026-03-21 16:29:01'),
(8, 'branding_logo_height_mobile', '36', 'Altura da logo no header mobile', '2026-03-21 16:29:01', '2026-03-21 16:29:01'),
(9, 'branding_logo_height_login', '82', 'Altura da logo na tela de login', '2026-03-21 16:29:01', '2026-03-21 16:29:01'),
(14, 'branding_favicon_path', '/assets/uploads/branding/favicon-20260322-095625-12bb539f.png', 'Caminho público do favicon do sistema', '2026-03-22 00:58:41', '2026-03-22 12:56:25'),
(29, 'company_address', 'R. Gelu Vervloet dos Santos, 590, Sala 1303 - Jardim Camburi, Vitória - ES. CEP: 29090-100', 'Endereço exibido no rodapé e PDF', '2026-03-22 12:29:16', '2026-03-22 12:55:52'),
(30, 'company_phone', '(27) 99603-4719', 'Telefone exibido no rodapé e PDF', '2026-03-22 12:29:16', '2026-03-27 01:05:38'),
(31, 'company_email', 'contato@rebecamedina.com.br', 'E-mail exibido no rodapé e PDF', '2026-03-22 12:29:16', '2026-03-22 12:55:52'),
(47, 'branding_logo_light_path', '/assets/uploads/branding/logo-light-20260322-141234-af832fe0.png', 'Logo usada no tema claro', '2026-03-22 16:25:33', '2026-03-22 17:12:34'),
(48, 'branding_logo_dark_path', '/assets/uploads/branding/logo-dark-20260322-144401-c9b4be20.png', 'Logo usada no tema escuro', '2026-03-22 16:25:33', '2026-03-22 17:44:01'),
(73, 'branding_premium_logo_variant', 'light', 'Logo escolhida para o preview/PDF premium', '2026-03-23 22:59:03', '2026-03-23 22:59:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_email`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-18 23:37:20'),
(2, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 1, 'Cadastro de nova proposta - Número da Proposta #1', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-18 23:56:02'),
(3, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 00:29:09'),
(4, 2, 'junior@rebecamedina.com.br', 'update_proposta', 'propostas', 1, 'Edição de proposta - Número da Proposta #1', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 00:29:47'),
(5, 2, 'junior@rebecamedina.com.br', 'update_proposta', 'propostas', 1, 'Edição de proposta - Número da Proposta #1', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 00:31:40'),
(6, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 00:41:58'),
(7, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 2, 'Cadastro de nova proposta - Número da Proposta #2', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 00:55:35'),
(8, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 14:48:19'),
(9, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 16:18:51'),
(10, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 16:47:43'),
(11, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 16:48:00'),
(12, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 16:48:47'),
(13, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 16:48:57'),
(14, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.171.158', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-21 16:49:54'),
(15, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 17:08:03'),
(16, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 17:08:15'),
(17, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 3, 'Cadastro de nova proposta - Número da Proposta #3', '187.36.171.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 17:17:34'),
(18, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 00:42:27'),
(19, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 01:08:43'),
(20, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 01:08:52'),
(21, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 01:14:03'),
(22, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 01:14:19'),
(23, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 01:15:38'),
(24, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 12:48:41'),
(25, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 12:54:07'),
(26, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 12:54:18'),
(27, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:11:01'),
(28, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:11:23'),
(29, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:11:34'),
(30, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:11:59'),
(31, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:12:06'),
(32, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:21:24'),
(33, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:21:33'),
(34, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:32:39'),
(35, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:32:45'),
(36, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:35:05'),
(37, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:35:12'),
(38, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-22 17:36:44'),
(39, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:38:22'),
(40, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:38:43'),
(41, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:40:46'),
(42, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:43:34'),
(43, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:48:26'),
(44, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:48:37'),
(45, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:52:25'),
(46, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:59:10'),
(47, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:59:14'),
(48, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:59:24'),
(49, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:59:29'),
(50, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:11:27'),
(51, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:11:31'),
(52, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:11:38'),
(53, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:35:39'),
(54, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:35:48'),
(55, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:56:54'),
(56, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:57:10'),
(57, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-23 09:07:58'),
(58, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.175.173', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-23 09:08:12'),
(59, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-23 09:08:16'),
(60, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 11:00:11'),
(61, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 14:37:31'),
(62, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 4, 'Cadastro de nova proposta - Número da Proposta #4', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 14:42:32'),
(63, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:02:34'),
(64, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:02:43'),
(65, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:05:20'),
(66, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:05:24'),
(67, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:06:42'),
(68, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:06:49'),
(69, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:39:57'),
(70, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:40:03'),
(71, 2, 'junior@rebecamedina.com.br', 'upload_attachment', 'proposta_attachments', 1, 'Upload de anexo PDF na proposta #4', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 15:58:28'),
(72, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 17:17:27'),
(73, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 18:01:23'),
(74, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 18:01:26'),
(75, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 18:38:41'),
(76, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 18:38:50'),
(77, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 19:12:41'),
(78, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 19:12:51'),
(79, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 19:12:55'),
(80, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '131.255.21.64', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 19:13:00'),
(81, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.175.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 20:01:11'),
(82, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 22:16:03'),
(83, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 22:21:31'),
(84, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 22:21:40'),
(85, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 22:57:46'),
(86, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 22:57:51'),
(87, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 23:16:20'),
(88, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-23 23:16:26'),
(89, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.142.77', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-24 00:15:20'),
(90, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '191.246.142.77', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-24 00:26:16'),
(91, 1, 'rebeca@rebecamedina.com.br', 'login', 'users', 1, 'Acesso ao sistema', '191.246.153.43', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-24 01:34:32'),
(92, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 18:51:41'),
(93, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 5, 'Cadastro de nova proposta - Número da Proposta #5', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 18:59:05'),
(94, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 6, 'Cadastro de nova proposta - Número da Proposta #6', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:02:50'),
(95, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 7, 'Cadastro de nova proposta - Número da Proposta #7', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:07:41'),
(96, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 8, 'Cadastro de nova proposta - Número da Proposta #8', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:14:40'),
(97, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 9, 'Cadastro de nova proposta - Número da Proposta #9', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:17:27'),
(98, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 10, 'Cadastro de nova proposta - Número da Proposta #10', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:22:50'),
(99, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 11, 'Cadastro de nova proposta - Número da Proposta #11', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:28:22'),
(100, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 12, 'Cadastro de nova proposta - Número da Proposta #12', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:30:36'),
(101, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 13, 'Cadastro de nova proposta - Número da Proposta #13', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:39:00'),
(102, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 14, 'Cadastro de nova proposta - Número da Proposta #14', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:43:01'),
(103, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 15, 'Cadastro de nova proposta - Número da Proposta #15', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:48:29'),
(104, 2, 'junior@rebecamedina.com.br', 'create_proposta', 'propostas', 16, 'Cadastro de nova proposta - Número da Proposta #16', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 19:50:19'),
(105, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-24 20:37:30'),
(106, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-24 20:42:36'),
(107, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-24 20:43:01'),
(108, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-24 20:52:23'),
(109, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.145.55', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-24 23:59:34'),
(110, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '191.246.152.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-25 10:46:54'),
(111, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-25 21:24:27'),
(112, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-25 22:41:31'),
(113, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-25 23:37:59'),
(114, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-26 01:57:26'),
(115, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-03-26 01:58:01'),
(116, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-26 09:47:57'),
(117, 1, 'rebeca@rebecamedina.com.br', 'login', 'users', 1, 'Acesso ao sistema', '191.246.131.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-26 09:51:12'),
(118, 1, 'rebeca@rebecamedina.com.br', 'login', 'users', 1, 'Acesso ao sistema', '191.246.131.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-26 09:51:19'),
(119, 1, 'rebeca@rebecamedina.com.br', 'login', 'users', 1, 'Acesso ao sistema', '191.246.131.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-26 09:52:39'),
(120, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 00:14:44'),
(121, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 01:04:55'),
(122, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 01:23:06'),
(123, 2, 'junior@rebecamedina.com.br', 'login', 'users', 2, 'Acesso ao sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 01:23:19'),
(124, 2, 'junior@rebecamedina.com.br', 'logout', 'users', 2, 'Saída do sistema', '187.36.169.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 01:33:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_envio`
--

CREATE TABLE `formas_envio` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_class` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#999999',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `formas_envio`
--

INSERT INTO `formas_envio` (`id`, `name`, `icon_class`, `color_hex`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'WhatsApp', 'fa-brands fa-whatsapp', '#25D366', 1, 1, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(2, 'E-mail', 'fa-solid fa-envelope', '#3B82F6', 1, 2, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(3, 'Telefone', 'fa-solid fa-phone', '#F59E0B', 1, 3, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(4, 'Presencial', 'fa-solid fa-handshake', '#A855F7', 1, 4, '2026-03-18 23:36:03', '2026-03-18 23:36:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notification_dispatches`
--

CREATE TABLE `notification_dispatches` (
  `id` bigint UNSIGNED NOT NULL,
  `proposta_id` bigint UNSIGNED NOT NULL,
  `alert_type` enum('followup','validity') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispatch_date` date NOT NULL,
  `recipient_email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispatch_status` enum('sent','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposal_documents`
--

CREATE TABLE `proposal_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `proposta_id` bigint UNSIGNED NOT NULL,
  `template_id` bigint UNSIGNED NOT NULL,
  `document_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposal_kind` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attention_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attention_role` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intro_context` text COLLATE utf8mb4_unicode_ci,
  `scope_intro` text COLLATE utf8mb4_unicode_ci,
  `closing_message` text COLLATE utf8mb4_unicode_ci,
  `validity_days` int UNSIGNED NOT NULL DEFAULT '30',
  `show_institutional` tinyint(1) NOT NULL DEFAULT '1',
  `show_services` tinyint(1) NOT NULL DEFAULT '1',
  `show_extra_services` tinyint(1) NOT NULL DEFAULT '1',
  `show_contacts_page` tinyint(1) NOT NULL DEFAULT '1',
  `cover_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `proposal_documents`
--

INSERT INTO `proposal_documents` (`id`, `proposta_id`, `template_id`, `document_title`, `proposal_kind`, `client_display_name`, `attention_to`, `attention_role`, `cover_subtitle`, `intro_context`, `scope_intro`, `closing_message`, `validity_days`, `show_institutional`, `show_services`, `show_extra_services`, `show_contacts_page`, `cover_image_path`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'Título do documento', 'Tipo da proposta', 'CONDOMÍNIO EDIFÍCIO ITAPARICA ON', 'Síndica Melissa', 'Síndica', 'Subtítulo da capa', 'Contexto introdutório aqui', '- Análise dos documentos vigente apontando eventuais necessidades de alteração e adequação;\r\n - Elaboração de uma nova minuta dos documentos de acordo com as alterações sugeridas ou solicitações dos condôminos e do corpo diretivo, com emissão de parecer (por e-mail), quando a solicitação não puder ser acatada, que será sempre direcionada ao síndico;\r\n - Participação em reunião virtual antes da assembleia, com o síndico e conselho para esclarecimentos de dúvidas prévias, após a elaboração da minuta proposta pelo escritório, antes do agendamento da assembleia, se for necessário;\r\n - Assessoria no procedimento de aprovação do documento, com participação em assembleia par dirimir dúvidas jurídicas, se houverem;\r\n - Realização de visita técnica in loco para validação da minuta de acordo com a estrutura física do condomínio.', 'Mensagem final aqui', 30, 1, 1, 1, 1, '', 2, 2, '2026-03-23 15:43:50', '2026-03-23 18:01:47'),
(2, 16, 1, 'Proposta de Honorários', '', 'CONDOMÍNIO RESIDENCIAL VILA BELA', 'Andressa Mendes', 'Síndica', '', '', 'Análise dos documentos vigente apontando eventuais necessidades de alteração e adequação;\r\nElaboração de uma nova minuta dos documentos de acordo com as alterações sugeridas ou solicitações dos condôminos e do corpo diretivo, com emissão de parecer (por e-mail), quando a solicitação não puder ser acatada, que será sempre direcionada ao síndico;\r\nParticipação em reunião virtual antes da assembleia, com o síndico e conselho para esclarecimentos de dúvidas prévias, após a elaboração da minuta proposta pelo escritório, antes do agendamento da assembleia, se for necessário;\r\nAssessoria no procedimento de aprovação do documento, com participação em assembleia par dirimir dúvidas jurídicas, se houverem;\r\nRealização de visita técnica in loco para validação da minuta de acordo com a estrutura física do condomínio.', '', 30, 1, 1, 1, 1, '', 2, 2, '2026-03-24 19:58:39', '2026-03-25 10:52:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposal_document_assets`
--

CREATE TABLE `proposal_document_assets` (
  `id` bigint UNSIGNED NOT NULL,
  `proposal_document_id` bigint UNSIGNED NOT NULL,
  `asset_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposal_document_options`
--

CREATE TABLE `proposal_document_options` (
  `id` bigint UNSIGNED NOT NULL,
  `proposal_document_id` bigint UNSIGNED NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scope_html` longtext COLLATE utf8mb4_unicode_ci,
  `fee_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_value` decimal(12,2) DEFAULT NULL,
  `amount_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` text COLLATE utf8mb4_unicode_ci,
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `proposal_document_options`
--

INSERT INTO `proposal_document_options` (`id`, `proposal_document_id`, `sort_order`, `title`, `scope_title`, `scope_html`, `fee_label`, `amount_value`, `amount_text`, `payment_terms`, `is_recommended`, `created_at`, `updated_at`) VALUES
(16, 1, 1, 'Título da opção', 'Título do escopo', 'Escopo de Opções comerciais', 'ATUALIZAÇÃO DE UM DOCUMENTO (SÓ O REGIMENTO)', 3240.00, 'Três Mil e Duzentos e Quarenta Reais', 'ENTRADA DE 30/% E PARCELAMENTO ATÉ 02\r\nPARCELAS', 1, '2026-03-23 23:22:40', '2026-03-23 23:22:40'),
(41, 2, 1, 'ATUALIZAÇÃO DA CONVENÇÃO', NULL, NULL, 'ATUALIZAÇÃO DA CONVENÇÃO', 3242.00, 'Três mil e duzentos e quarenta e dois reais', 'ENTRADA DE 30% E O RESTANTE EM ATÉ 2 PARCELAS.', 0, '2026-03-25 21:33:51', '2026-03-25 21:33:51'),
(42, 2, 2, '', NULL, NULL, 'ATUALIZAÇÃO DA CONVENÇÃO', 3242.00, 'Três mil e duzentos e quarenta e dois reais', 'ENTRADA DE 30% E O RESTANTE EM ATÉ 2 PARCELAS.', 0, '2026-03-25 21:33:51', '2026-03-25 21:33:51'),
(43, 2, 3, 'Plano 3', 'Plano 3 o melhor', 'Aqui está um material de estudo denso e detalhado sobre Teoria da Pena e Ação Penal, elaborado exclusivamente a partir da transcrição da aula e do material de quadro fornecidos, com os desvios de assunto descartados para focar no conteúdo jurídico.\r\nTEMA 1: SANÇÃO PENAL (PENAS E MEDIDAS DE SEGURANÇA)\r\nA sanção penal é o gênero do qual derivam duas espécies no', 'COMBO DOS DOIS DOCUMENTOS', 6159.80, 'Seis Mil e Cento e Cinquenta e Nove Reais e Oitenta Centavos', 'ENTRADA DE 30% E O RESTANTE EM ATÉ 2 PARCELAS.', 1, '2026-03-25 21:33:51', '2026-03-25 21:33:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposal_templates`
--

CREATE TABLE `proposal_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `proposal_templates`
--

INSERT INTO `proposal_templates` (`id`, `slug`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'aquarela-premium', 'Aquarela Premium', 'Template base da proposta premium inspirado no modelo 007.2026.', 1, '2026-03-23 10:42:54', '2026-03-23 10:42:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `propostas`
--

CREATE TABLE `propostas` (
  `id` bigint UNSIGNED NOT NULL,
  `proposal_year` smallint UNSIGNED NOT NULL,
  `proposal_seq` int UNSIGNED NOT NULL,
  `proposal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposal_date` date NOT NULL,
  `client_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `administradora_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `proposal_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `closed_total` decimal(12,2) DEFAULT NULL,
  `requester_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requester_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_referral` tinyint(1) NOT NULL DEFAULT '0',
  `referral_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_method_id` bigint UNSIGNED NOT NULL,
  `response_status_id` bigint UNSIGNED NOT NULL,
  `refusal_reason` text COLLATE utf8mb4_unicode_ci,
  `followup_date` date DEFAULT NULL,
  `validity_days` int UNSIGNED NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `propostas`
--

INSERT INTO `propostas` (`id`, `proposal_year`, `proposal_seq`, `proposal_code`, `proposal_date`, `client_name`, `administradora_id`, `service_id`, `proposal_total`, `closed_total`, `requester_name`, `requester_phone`, `contact_email`, `has_referral`, `referral_name`, `send_method_id`, `response_status_id`, `refusal_reason`, `followup_date`, `validity_days`, `notes`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2025, 1, '001.2025', '2025-02-03', 'CONDOMINIO RESIDENCIAL MONTE OLIMPO', 1, 1, 3036.00, NULL, 'Márcia Alves', '(27) 99738-1252', 'alveswanderson944@gmail.com', 1, 'Lourdes LFControl', 1, 5, 'Condomínio decidiu fechar com outro escritório.', '2025-03-03', 30, NULL, 2, 2, '2026-03-18 23:56:02', '2026-03-21 15:58:40'),
(2, 2025, 2, '002.2025', '2025-02-03', 'CONDOMINIO RESIDENCIAL MONTE MORIAH', 1, 1, 1518.00, NULL, 'Márcia Alves', '(27) 99738-1252', 'alveswanderson944@gmail.com', 1, 'LF Control', 1, 5, 'Cliente decidiu não ter juridico.', '2025-03-03', 30, NULL, 2, 2, '2026-03-19 00:55:35', '2026-03-21 15:58:40'),
(3, 2025, 3, '003.2025', '2025-02-10', 'CONDOMÍNIO ALICE NEFFA', 5, 6, 1518.00, NULL, 'Marcele Miranda', '(27) 99748-4166', 'marcelealves.miranda@gmail.com', 0, NULL, 1, 5, 'Fechou com outro cliente.', '2025-03-09', 30, NULL, 2, 2, '2026-03-21 17:17:34', '2026-03-21 17:17:34'),
(4, 2026, 1, '001.2026', '2026-01-16', 'CONDOMÍNIO EDIFÍCIO ITAPARICA ON', 6, 4, 3240.00, NULL, 'Síndica Melissa', '(27) 99894-4618', 'melissasindicaprof@gmail.com', 0, NULL, 1, 2, NULL, '2026-02-16', 30, NULL, 2, 2, '2026-03-23 14:42:32', '2026-03-23 14:42:32'),
(5, 2026, 2, '002.2026', '2026-02-02', 'CONDOMINIO EDIFICIO LOLITA', 7, 4, 6084.00, NULL, 'Luciano', '', '', 0, NULL, 1, 5, 'Não houve retorno por parte do solicitante.', '2026-03-02', 30, NULL, 2, 2, '2026-03-24 18:59:05', '2026-03-24 18:59:05'),
(6, 2026, 3, '003.2026', '2026-02-23', 'CONDOMÍNIO PALM BEACH', 1, 4, 8927.00, NULL, 'Lourdes Ferreira', '', '', 0, NULL, 1, 2, NULL, '2026-03-23', 30, NULL, 2, 2, '2026-03-24 19:02:50', '2026-03-24 19:02:50'),
(7, 2026, 4, '004.2026', '2026-02-23', 'CONDOMÍNIO ED. ANA CAPRI', 1, 5, 2979.00, NULL, 'Andreia', '', '', 0, NULL, 1, 2, NULL, '2026-02-23', 30, NULL, 2, 2, '2026-03-24 19:07:41', '2026-03-24 19:07:41'),
(8, 2026, 5, '005.2026', '2026-03-09', 'CONDOMÍNIO GUARANI', 8, 4, 8927.00, NULL, 'Cenir', '', '', 0, NULL, 1, 2, NULL, '2026-04-09', 30, NULL, 2, 2, '2026-03-24 19:14:40', '2026-03-24 19:14:40'),
(9, 2026, 6, '006.2026', '2026-03-10', 'CONDOMÍNIO ED. PALLADIUM', 4, 4, 6159.80, NULL, 'Sr. Henrique', '', '', 0, NULL, 2, 2, NULL, '2026-04-10', 30, NULL, 2, 2, '2026-03-24 19:17:27', '2026-03-24 19:17:27'),
(10, 2026, 7, '007.2026', '2026-03-13', 'CONDOMÍNIO DO EDIFÍCIO AQUARELA', 4, 5, 2431.50, 2431.50, 'Márcia Ágata', '', '', 0, NULL, 1, 4, NULL, NULL, 30, NULL, 2, 2, '2026-03-24 19:22:50', '2026-03-24 19:22:50'),
(11, 2026, 8, '008.2026', '2026-03-14', 'CONDOMÍNIO DO EDIFÍCIO VICTOR COSER SERAPHIM', 9, 5, 3036.00, NULL, 'Ismael', '', '', 0, NULL, 1, 5, 'Condomínio optou por escolher outro escritório.', '2026-04-14', 30, NULL, 2, 2, '2026-03-24 19:28:22', '2026-03-24 19:28:22'),
(12, 2026, 9, '009.2026', '2026-03-14', 'CONDOMINIO CAPRI', 1, 1, 3242.00, NULL, 'Andreia', '', '', 0, NULL, 1, 5, 'Síndica vai aguardar próxima assembléia.', '2026-04-14', 30, NULL, 2, 2, '2026-03-24 19:30:36', '2026-03-24 19:30:36'),
(13, 2026, 10, '010.2026', '2026-03-18', 'CONDOMÍNIO ED. PRAIA DA ENSEADA', 10, 4, 6159.80, NULL, 'Síndica Rosângela', '', '', 0, NULL, 1, 2, NULL, '2026-04-18', 30, NULL, 2, 2, '2026-03-24 19:39:00', '2026-03-24 19:39:00'),
(14, 2026, 11, '011.2026', '2026-03-23', 'CONDOMÍNIO SOLAR MATA DA PRAIA', 2, 1, 3242.00, NULL, 'José William', '', '', 0, NULL, 1, 2, NULL, '2026-04-24', 30, NULL, 2, 2, '2026-03-24 19:43:01', '2026-03-24 19:43:01'),
(15, 2026, 12, '012.2026', '2026-03-23', 'CONDOMÍNIO RESIDENCIAL VILA BELA', 12, 1, 1621.00, NULL, 'Andressa', '', '', 0, NULL, 1, 2, NULL, '2026-04-24', 30, NULL, 2, 2, '2026-03-24 19:48:29', '2026-03-24 19:48:29'),
(16, 2026, 13, '013.2026', '2026-03-23', 'CONDOMÍNIO RESIDENCIAL VILA BELA', 12, 4, 6159.80, NULL, 'Andressa', '', '', 0, NULL, 1, 2, NULL, '2026-04-24', 30, NULL, 2, 2, '2026-03-24 19:50:19', '2026-03-24 19:50:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposta_attachments`
--

CREATE TABLE `proposta_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `proposta_id` bigint UNSIGNED NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relative_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'application/pdf',
  `file_size` bigint UNSIGNED NOT NULL DEFAULT '0',
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `proposta_attachments`
--

INSERT INTO `proposta_attachments` (`id`, `proposta_id`, `original_name`, `stored_name`, `relative_path`, `mime_type`, `file_size`, `uploaded_by`, `created_at`) VALUES
(1, 4, '001._2026_-_COND._ITAPARICA_ON.pdf', '20260323_125826_7851da31af6a.pdf', 'storage/uploads/propostas/4/20260323_125826_7851da31af6a.pdf', 'application/pdf', 4055735, 2, '2026-03-23 15:58:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `proposta_history`
--

CREATE TABLE `proposta_history` (
  `id` bigint UNSIGNED NOT NULL,
  `proposta_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `proposta_history`
--

INSERT INTO `proposta_history` (`id`, `proposta_id`, `user_id`, `user_email`, `action`, `summary`, `payload_json`, `created_at`) VALUES
(1, 1, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 1, \"notes\": null, \"service_id\": 1, \"client_name\": \"CONDOMINIO RESIDENCIAL MONTE OLIMPO\", \"status_name\": \"Enviada\", \"closed_total\": null, \"service_name\": \"Assessoria Jurídica Condominial\", \"contact_email\": \"alveswanderson944@gmail.com\", \"followup_date\": \"2025-03-03\", \"proposal_date\": \"2025-02-03\", \"validity_days\": 30, \"proposal_total\": 3036, \"refusal_reason\": null, \"requester_name\": \"Márcia Alves\", \"send_method_id\": 1, \"requester_phone\": \"(27) 99738-1252\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 1, \"response_status_id\": 1, \"administradora_name\": \"LF Control\"}}', '2026-03-18 23:56:02'),
(2, 1, 2, 'junior@rebecamedina.com.br', 'update', 'Proposta atualizada (2 alteração(ões)).', '{\"changes\": [{\"to\": \"Sim\", \"from\": \"Não\", \"field\": \"has_referral\", \"label\": \"Houve indicação\"}, {\"to\": \"Lourdes LFControl\", \"from\": \"—\", \"field\": \"referral_name\", \"label\": \"Nome da indicação\"}]}', '2026-03-19 00:29:47'),
(3, 1, 2, 'junior@rebecamedina.com.br', 'update', 'Proposta atualizada (2 alteração(ões)).', '{\"changes\": [{\"to\": \"Declinada\", \"from\": \"Enviada\", \"field\": \"response_status_id\", \"label\": \"Status\"}, {\"to\": \"Condomínio decidiu fechar com outro escritório.\", \"from\": \"—\", \"field\": \"refusal_reason\", \"label\": \"Motivo da recusa\"}]}', '2026-03-19 00:31:40'),
(4, 2, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 2, \"notes\": null, \"service_id\": 1, \"client_name\": \"CONDOMINIO RESIDENCIAL MONTE MORIAH\", \"status_name\": \"Declinada\", \"closed_total\": null, \"has_referral\": 1, \"service_name\": \"Assessoria Jurídica Condominial\", \"contact_email\": \"alveswanderson944@gmail.com\", \"followup_date\": \"2025-03-03\", \"proposal_date\": \"2025-02-03\", \"referral_name\": \"LF Control\", \"validity_days\": 30, \"proposal_total\": 1518, \"refusal_reason\": \"Cliente decidiu não ter juridico.\", \"requester_name\": \"Márcia Alves\", \"send_method_id\": 1, \"requester_phone\": \"(27) 99738-1252\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 1, \"response_status_id\": 5, \"administradora_name\": \"LF Control\"}}', '2026-03-19 00:55:35'),
(5, 3, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 3, \"notes\": null, \"service_id\": 6, \"client_name\": \"CONDOMÍNIO ALICE NEFFA\", \"status_name\": \"Declinada\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Notificação Extrajudicial\", \"contact_email\": \"marcelealves.miranda@gmail.com\", \"followup_date\": \"2025-03-09\", \"proposal_date\": \"2025-02-10\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 1518, \"refusal_reason\": \"Fechou com outro cliente.\", \"requester_name\": \"Marcele Miranda\", \"send_method_id\": 1, \"requester_phone\": \"(27) 99748-4166\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 5, \"response_status_id\": 5, \"administradora_name\": \"Marcele Miranda\"}}', '2026-03-21 17:17:34'),
(6, 4, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 4, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO EDIFÍCIO ITAPARICA ON\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"melissasindicaprof@gmail.com\", \"followup_date\": \"2026-02-16\", \"proposal_date\": \"2026-01-16\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 3240, \"refusal_reason\": null, \"requester_name\": \"Síndica Melissa\", \"send_method_id\": 1, \"requester_phone\": \"(27) 99894-4618\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 6, \"response_status_id\": 2, \"administradora_name\": \"Melissa Calzavara\"}}', '2026-03-23 14:42:32'),
(7, 4, 2, 'junior@rebecamedina.com.br', 'attachment_upload', 'PDF anexado à proposta.', '{\"attachment_id\": 1, \"original_name\": \"001._2026_-_COND._ITAPARICA_ON.pdf\"}', '2026-03-23 15:58:28'),
(8, 5, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 5, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMINIO EDIFICIO LOLITA\", \"status_name\": \"Declinada\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-03-02\", \"proposal_date\": \"2026-02-02\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 6084, \"refusal_reason\": \"Não houve retorno por parte do solicitante.\", \"requester_name\": \"Luciano\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 7, \"response_status_id\": 5, \"administradora_name\": \"AC Administradora\"}}', '2026-03-24 18:59:05'),
(9, 6, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 6, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO PALM BEACH\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-03-23\", \"proposal_date\": \"2026-02-23\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 8927, \"refusal_reason\": null, \"requester_name\": \"Lourdes Ferreira\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 1, \"response_status_id\": 2, \"administradora_name\": \"LF Control\"}}', '2026-03-24 19:02:50'),
(10, 7, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 7, \"notes\": null, \"service_id\": 5, \"client_name\": \"CONDOMÍNIO ED. ANA CAPRI\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Parecer Jurídico\", \"contact_email\": \"\", \"followup_date\": \"2026-02-23\", \"proposal_date\": \"2026-02-23\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 2979, \"refusal_reason\": null, \"requester_name\": \"Andreia\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 1, \"response_status_id\": 2, \"administradora_name\": \"LF Control\"}}', '2026-03-24 19:07:41'),
(11, 8, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 8, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO GUARANI\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-04-09\", \"proposal_date\": \"2026-03-09\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 8927, \"refusal_reason\": null, \"requester_name\": \"Cenir\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 8, \"response_status_id\": 2, \"administradora_name\": \"Harmonize Administradora\"}}', '2026-03-24 19:14:40'),
(12, 9, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 9, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO ED. PALLADIUM\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-04-10\", \"proposal_date\": \"2026-03-10\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 6159.8, \"refusal_reason\": null, \"requester_name\": \"Sr. Henrique\", \"send_method_id\": 2, \"requester_phone\": \"\", \"send_method_name\": \"E-mail\", \"administradora_id\": 4, \"response_status_id\": 2, \"administradora_name\": \"Condonal\"}}', '2026-03-24 19:17:27'),
(13, 10, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 10, \"notes\": null, \"service_id\": 5, \"client_name\": \"CONDOMÍNIO DO EDIFÍCIO AQUARELA\", \"status_name\": \"Aprovado\", \"closed_total\": 2431.5, \"has_referral\": 0, \"service_name\": \"Parecer Jurídico\", \"contact_email\": \"\", \"followup_date\": null, \"proposal_date\": \"2026-03-13\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 2431.5, \"refusal_reason\": null, \"requester_name\": \"Márcia Ágata\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 4, \"response_status_id\": 4, \"administradora_name\": \"Condonal\"}}', '2026-03-24 19:22:50'),
(14, 11, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 11, \"notes\": null, \"service_id\": 5, \"client_name\": \"CONDOMÍNIO DO EDIFÍCIO VICTOR COSER SERAPHIM\", \"status_name\": \"Declinada\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Parecer Jurídico\", \"contact_email\": \"\", \"followup_date\": \"2026-04-14\", \"proposal_date\": \"2026-03-14\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 3036, \"refusal_reason\": \"Condomínio optou por escolher outro escritório.\", \"requester_name\": \"Ismael\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 9, \"response_status_id\": 5, \"administradora_name\": \"Ismael\"}}', '2026-03-24 19:28:22'),
(15, 12, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 12, \"notes\": null, \"service_id\": 1, \"client_name\": \"CONDOMINIO CAPRI\", \"status_name\": \"Declinada\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Assessoria Jurídica Condominial\", \"contact_email\": \"\", \"followup_date\": \"2026-04-14\", \"proposal_date\": \"2026-03-14\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 3242, \"refusal_reason\": \"Síndica vai aguardar próxima assembléia.\", \"requester_name\": \"Andreia\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 1, \"response_status_id\": 5, \"administradora_name\": \"LF Control\"}}', '2026-03-24 19:30:36'),
(16, 13, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 13, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO ED. PRAIA DA ENSEADA\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-04-18\", \"proposal_date\": \"2026-03-18\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 6159.8, \"refusal_reason\": null, \"requester_name\": \"Síndica Rosângela\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 10, \"response_status_id\": 2, \"administradora_name\": \"Rosângela Heringer\"}}', '2026-03-24 19:39:00'),
(17, 14, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 14, \"notes\": null, \"service_id\": 1, \"client_name\": \"CONDOMÍNIO SOLAR MATA DA PRAIA\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Assessoria Jurídica Condominial\", \"contact_email\": \"\", \"followup_date\": \"2026-04-24\", \"proposal_date\": \"2026-03-23\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 3242, \"refusal_reason\": null, \"requester_name\": \"José William\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 2, \"response_status_id\": 2, \"administradora_name\": \"Paula Cordeiro\"}}', '2026-03-24 19:43:01'),
(18, 15, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 15, \"notes\": null, \"service_id\": 1, \"client_name\": \"CONDOMÍNIO RESIDENCIAL VILA BELA\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Assessoria Jurídica Condominial\", \"contact_email\": \"\", \"followup_date\": \"2026-04-24\", \"proposal_date\": \"2026-03-23\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 1621, \"refusal_reason\": null, \"requester_name\": \"Andressa\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 12, \"response_status_id\": 2, \"administradora_name\": \"Andressa Mendes\"}}', '2026-03-24 19:48:29'),
(19, 16, 2, 'junior@rebecamedina.com.br', 'create', 'Proposta cadastrada.', '{\"snapshot\": {\"id\": 16, \"notes\": null, \"service_id\": 4, \"client_name\": \"CONDOMÍNIO RESIDENCIAL VILA BELA\", \"status_name\": \"Aguardando Retorno\", \"closed_total\": null, \"has_referral\": 0, \"service_name\": \"Regularização de Convenção e Regimento\", \"contact_email\": \"\", \"followup_date\": \"2026-04-24\", \"proposal_date\": \"2026-03-23\", \"referral_name\": null, \"validity_days\": 30, \"proposal_total\": 6159.8, \"refusal_reason\": null, \"requester_name\": \"Andressa\", \"send_method_id\": 1, \"requester_phone\": \"\", \"send_method_name\": \"WhatsApp\", \"administradora_id\": 12, \"response_status_id\": 2, \"administradora_name\": \"Andressa Mendes\"}}', '2026-03-24 19:50:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `name`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Assessoria Jurídica Condominial', 'Consultoria e atuação jurídica preventiva/contenciosa para condomínios.', 1, 1, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(2, 'Cobrança Extrajudicial', 'Recuperação amigável de créditos condominiais.', 1, 2, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(3, 'Cobrança Judicial', 'Execução e cobrança judicial de débitos condominiais.', 1, 3, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(4, 'Regularização de Convenção e Regimento', 'Adequação documental e atualização normativa.', 1, 4, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(5, 'Parecer Jurídico', 'Elaboração de parecer jurídico sob demanda.', 1, 5, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(6, 'Notificação Extrajudicial', 'Análise, confecção e resposta de notificação extrajudicial', 1, 0, '2026-03-21 17:13:39', '2026-03-21 17:13:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_retorno`
--

CREATE TABLE `status_retorno` (
  `id` bigint UNSIGNED NOT NULL,
  `system_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#999999',
  `requires_closed_value` tinyint(1) NOT NULL DEFAULT '0',
  `requires_refusal_reason` tinyint(1) NOT NULL DEFAULT '0',
  `stop_followup_alert` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `status_retorno`
--

INSERT INTO `status_retorno` (`id`, `system_key`, `name`, `color_hex`, `requires_closed_value`, `requires_refusal_reason`, `stop_followup_alert`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'sent', 'Enviada', '#3B82F6', 0, 0, 0, 1, 1, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(2, 'awaiting_return', 'Aguardando Retorno', '#F59E0B', 0, 0, 0, 1, 2, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(3, 'in_negotiation', 'Em Negociação', '#8B5CF6', 0, 0, 1, 1, 3, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(4, 'approved', 'Aprovado', '#10B981', 1, 0, 1, 1, 4, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(5, 'declined', 'Declinada', '#EF4444', 0, 1, 1, 1, 5, '2026-03-18 23:36:03', '2026-03-18 23:36:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_modules`
--

CREATE TABLE `system_modules` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_class` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_prefix` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `system_modules`
--

INSERT INTO `system_modules` (`id`, `slug`, `name`, `icon_class`, `route_prefix`, `is_enabled`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'propostas', 'Controle de Propostas', 'fa-solid fa-file-signature', '/propostas', 1, 1, '2026-03-18 23:36:03', '2026-03-18 23:36:03'),
(2, 'dashboard', 'Dashboard', 'fa-solid fa-chart-line', '/dashboard', 1, 2, '2026-03-22 12:29:16', '2026-03-22 12:29:16'),
(3, 'logs', 'Logs', 'fa-solid fa-clock-rotate-left', '/logs', 1, 3, '2026-03-22 12:29:16', '2026-03-22 12:29:16'),
(4, 'config', 'Configurações', 'fa-solid fa-gear', '/config', 1, 4, '2026-03-22 12:29:16', '2026-03-22 12:29:16'),
(5, 'busca', 'Busca', 'fa-solid fa-magnifying-glass', '/busca', 1, 3, '2026-03-27 00:14:10', '2026-03-27 00:14:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','comum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'comum',
  `theme_preference` enum('light','dark') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dark',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_protected` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `theme_preference`, `is_active`, `is_protected`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Rebeca Medina', 'rebeca@rebecamedina.com.br', '$2y$10$Ltns6qsg9FA2HFNQRWl.MeEZvXS.j09xzqt6JRmwO4ppRFiNNKN8q', 'superadmin', 'light', 1, 1, '2026-03-26 06:52:39', '2026-03-18 23:36:03', '2026-03-26 09:52:39'),
(2, 'Junior Amorim', 'junior@rebecamedina.com.br', '$2y$10$.O0KZddYB83AfaZYOJDV8.u68sn7EEuQ2GM7uaVOcS5f.9Wb53FVy', 'superadmin', 'dark', 1, 1, '2026-03-26 22:23:19', '2026-03-18 23:36:03', '2026-03-27 01:23:19');

--
-- Acionadores `users`
--
DELIMITER $$
CREATE TRIGGER `trg_users_protect_delete` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    IF OLD.is_protected = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Este usuário protegido não pode ser excluído.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_users_protect_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.is_protected = 1 AND NEW.role <> 'superadmin' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuário protegido não pode perder o perfil de superadmin.';
    END IF;

    IF OLD.is_protected = 1 AND NEW.is_active = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuário protegido não pode ser desativado.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_module_permissions`
--

CREATE TABLE `user_module_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradoras`
--
ALTER TABLE `administradoras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_administradoras_name_type` (`name`,`type`),
  ADD KEY `idx_administradoras_active` (`is_active`),
  ADD KEY `idx_administradoras_type` (`type`);

--
-- Índices de tabela `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_app_settings_key` (`setting_key`);

--
-- Índices de tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_logs_user` (`user_id`),
  ADD KEY `idx_audit_logs_action` (`action`),
  ADD KEY `idx_audit_logs_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_audit_logs_created_at` (`created_at`);

--
-- Índices de tabela `formas_envio`
--
ALTER TABLE `formas_envio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_formas_envio_name` (`name`),
  ADD KEY `idx_formas_envio_active` (`is_active`);

--
-- Índices de tabela `notification_dispatches`
--
ALTER TABLE `notification_dispatches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_notification_dispatch_daily` (`proposta_id`,`alert_type`,`dispatch_date`,`recipient_email`),
  ADD KEY `idx_notification_dispatch_date` (`dispatch_date`);

--
-- Índices de tabela `proposal_documents`
--
ALTER TABLE `proposal_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_proposal_documents_proposta` (`proposta_id`),
  ADD KEY `idx_proposal_documents_template` (`template_id`),
  ADD KEY `fk_proposal_documents_created_by` (`created_by`),
  ADD KEY `fk_proposal_documents_updated_by` (`updated_by`);

--
-- Índices de tabela `proposal_document_assets`
--
ALTER TABLE `proposal_document_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proposal_document_assets_document` (`proposal_document_id`);

--
-- Índices de tabela `proposal_document_options`
--
ALTER TABLE `proposal_document_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proposal_document_options_document` (`proposal_document_id`);

--
-- Índices de tabela `proposal_templates`
--
ALTER TABLE `proposal_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_proposal_templates_slug` (`slug`);

--
-- Índices de tabela `propostas`
--
ALTER TABLE `propostas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_propostas_code` (`proposal_code`),
  ADD UNIQUE KEY `uq_propostas_year_seq` (`proposal_year`,`proposal_seq`),
  ADD KEY `idx_propostas_proposal_date` (`proposal_date`),
  ADD KEY `idx_propostas_followup_date` (`followup_date`),
  ADD KEY `idx_propostas_status` (`response_status_id`),
  ADD KEY `idx_propostas_administradora` (`administradora_id`),
  ADD KEY `idx_propostas_servico` (`service_id`),
  ADD KEY `idx_propostas_created_by` (`created_by`),
  ADD KEY `fk_propostas_forma_envio` (`send_method_id`),
  ADD KEY `fk_propostas_updated_by` (`updated_by`);

--
-- Índices de tabela `proposta_attachments`
--
ALTER TABLE `proposta_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proposta_attachments_proposta` (`proposta_id`),
  ADD KEY `idx_proposta_attachments_uploaded_by` (`uploaded_by`);

--
-- Índices de tabela `proposta_history`
--
ALTER TABLE `proposta_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proposta_history_proposta` (`proposta_id`),
  ADD KEY `idx_proposta_history_action` (`action`),
  ADD KEY `fk_proposta_history_user` (`user_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_servicos_name` (`name`),
  ADD KEY `idx_servicos_active` (`is_active`);

--
-- Índices de tabela `status_retorno`
--
ALTER TABLE `status_retorno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_status_retorno_system_key` (`system_key`),
  ADD UNIQUE KEY `uq_status_retorno_name` (`name`),
  ADD KEY `idx_status_retorno_active` (`is_active`);

--
-- Índices de tabela `system_modules`
--
ALTER TABLE `system_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_system_modules_slug` (`slug`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- Índices de tabela `user_module_permissions`
--
ALTER TABLE `user_module_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_module_permissions_user_module` (`user_id`,`module_id`),
  ADD KEY `idx_user_module_permissions_module` (`module_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradoras`
--
ALTER TABLE `administradoras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de tabela `formas_envio`
--
ALTER TABLE `formas_envio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `notification_dispatches`
--
ALTER TABLE `notification_dispatches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `proposal_documents`
--
ALTER TABLE `proposal_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `proposal_document_assets`
--
ALTER TABLE `proposal_document_assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `proposal_document_options`
--
ALTER TABLE `proposal_document_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `proposal_templates`
--
ALTER TABLE `proposal_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `propostas`
--
ALTER TABLE `propostas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `proposta_attachments`
--
ALTER TABLE `proposta_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `proposta_history`
--
ALTER TABLE `proposta_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `status_retorno`
--
ALTER TABLE `status_retorno`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `system_modules`
--
ALTER TABLE `system_modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `user_module_permissions`
--
ALTER TABLE `user_module_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `notification_dispatches`
--
ALTER TABLE `notification_dispatches`
  ADD CONSTRAINT `fk_notification_dispatch_proposta` FOREIGN KEY (`proposta_id`) REFERENCES `propostas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `proposal_documents`
--
ALTER TABLE `proposal_documents`
  ADD CONSTRAINT `fk_proposal_documents_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposal_documents_proposta` FOREIGN KEY (`proposta_id`) REFERENCES `propostas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposal_documents_template` FOREIGN KEY (`template_id`) REFERENCES `proposal_templates` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposal_documents_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `proposal_document_assets`
--
ALTER TABLE `proposal_document_assets`
  ADD CONSTRAINT `fk_proposal_document_assets_document` FOREIGN KEY (`proposal_document_id`) REFERENCES `proposal_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `proposal_document_options`
--
ALTER TABLE `proposal_document_options`
  ADD CONSTRAINT `fk_proposal_document_options_document` FOREIGN KEY (`proposal_document_id`) REFERENCES `proposal_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `propostas`
--
ALTER TABLE `propostas`
  ADD CONSTRAINT `fk_propostas_administradora` FOREIGN KEY (`administradora_id`) REFERENCES `administradoras` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_propostas_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_propostas_forma_envio` FOREIGN KEY (`send_method_id`) REFERENCES `formas_envio` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_propostas_servico` FOREIGN KEY (`service_id`) REFERENCES `servicos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_propostas_status_retorno` FOREIGN KEY (`response_status_id`) REFERENCES `status_retorno` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_propostas_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `proposta_attachments`
--
ALTER TABLE `proposta_attachments`
  ADD CONSTRAINT `fk_proposta_attachments_proposta` FOREIGN KEY (`proposta_id`) REFERENCES `propostas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposta_attachments_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `proposta_history`
--
ALTER TABLE `proposta_history`
  ADD CONSTRAINT `fk_proposta_history_proposta` FOREIGN KEY (`proposta_id`) REFERENCES `propostas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposta_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `user_module_permissions`
--
ALTER TABLE `user_module_permissions`
  ADD CONSTRAINT `fk_user_module_permissions_module` FOREIGN KEY (`module_id`) REFERENCES `system_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_module_permissions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- =========================================================
-- CLIENTES MODULE
-- =========================================================
CREATE TABLE IF NOT EXISTS client_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scope VARCHAR(50) NOT NULL,
    name VARCHAR(120) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 999,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_client_types_scope_name (scope, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_entities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('pf','pj') NOT NULL DEFAULT 'pf',
    profile_scope ENUM('avulso','contato') NOT NULL DEFAULT 'avulso',
    role_tag VARCHAR(50) NOT NULL DEFAULT 'outro',
    display_name VARCHAR(180) NOT NULL,
    legal_name VARCHAR(180) NULL,
    cpf_cnpj VARCHAR(32) NULL,
    rg_ie VARCHAR(32) NULL,
    gender VARCHAR(20) NULL,
    nationality VARCHAR(80) NULL,
    birth_date DATE NULL,
    profession VARCHAR(120) NULL,
    marital_status VARCHAR(50) NULL,
    pis VARCHAR(32) NULL,
    spouse_name VARCHAR(180) NULL,
    father_name VARCHAR(180) NULL,
    mother_name VARCHAR(180) NULL,
    children_info TEXT NULL,
    ctps VARCHAR(32) NULL,
    cnae VARCHAR(32) NULL,
    state_registration VARCHAR(32) NULL,
    municipal_registration VARCHAR(32) NULL,
    opening_date DATE NULL,
    legal_representative VARCHAR(180) NULL,
    phones_json LONGTEXT NULL,
    emails_json LONGTEXT NULL,
    primary_address_json LONGTEXT NULL,
    billing_address_json LONGTEXT NULL,
    shareholders_json LONGTEXT NULL,
    notes TEXT NULL,
    description LONGTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    inactive_reason VARCHAR(255) NULL,
    contract_end_date DATE NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_entities_scope (profile_scope),
    KEY idx_client_entities_role (role_tag),
    KEY idx_client_entities_active (is_active),
    KEY idx_client_entities_document (cpf_cnpj)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_condominiums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    condominium_type_id INT NULL,
    has_blocks TINYINT(1) NOT NULL DEFAULT 0,
    cnpj VARCHAR(32) NULL,
    cnae VARCHAR(32) NULL,
    state_registration VARCHAR(32) NULL,
    municipal_registration VARCHAR(32) NULL,
    address_json LONGTEXT NULL,
    syndico_entity_id INT NULL,
    administradora_entity_id INT NULL,
    bank_details TEXT NULL,
    characteristics LONGTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    inactive_reason VARCHAR(255) NULL,
    contract_end_date DATE NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_condominiums_name (name),
    KEY idx_client_condominiums_type (condominium_type_id),
    CONSTRAINT fk_client_condominium_type FOREIGN KEY (condominium_type_id) REFERENCES client_types(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_condominium_syndic FOREIGN KEY (syndico_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_condominium_admin FOREIGN KEY (administradora_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_condominium_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    condominium_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_blocks_condo (condominium_id),
    CONSTRAINT fk_client_blocks_condo FOREIGN KEY (condominium_id) REFERENCES client_condominiums(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    condominium_id INT NOT NULL,
    block_id INT NULL,
    unit_type_id INT NULL,
    unit_number VARCHAR(50) NOT NULL,
    owner_entity_id INT NULL,
    tenant_entity_id INT NULL,
    owner_notes TEXT NULL,
    tenant_notes TEXT NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_units_condo (condominium_id),
    KEY idx_client_units_block (block_id),
    KEY idx_client_units_type (unit_type_id),
    KEY idx_client_units_owner (owner_entity_id),
    KEY idx_client_units_tenant (tenant_entity_id),
    CONSTRAINT fk_client_units_condo FOREIGN KEY (condominium_id) REFERENCES client_condominiums(id) ON DELETE CASCADE,
    CONSTRAINT fk_client_units_block FOREIGN KEY (block_id) REFERENCES client_condominium_blocks(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_type FOREIGN KEY (unit_type_id) REFERENCES client_types(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_owner FOREIGN KEY (owner_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_tenant FOREIGN KEY (tenant_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    related_type VARCHAR(40) NOT NULL,
    related_id INT NOT NULL,
    file_role ENUM('documento','contrato','outro') NOT NULL DEFAULT 'documento',
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    relative_path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NULL,
    file_size INT NOT NULL DEFAULT 0,
    uploaded_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_attachments_related (related_type, related_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_timelines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    related_type VARCHAR(40) NOT NULL,
    related_id INT NOT NULL,
    note LONGTEXT NOT NULL,
    user_id INT NULL,
    user_email VARCHAR(190) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_timelines_related (related_type, related_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO client_types (scope, name, is_active, sort_order)
VALUES
('condominium','Residencial',1,1),
('condominium','Comercial',1,2),
('condominium','Misto',1,3),
('unit','Apartamento',1,1),
('unit','Sala',1,2),
('unit','Loja',1,3)
ON DUPLICATE KEY UPDATE is_active = VALUES(is_active);

INSERT INTO system_modules (name, slug, icon_class, route_prefix, sort_order, is_enabled)
VALUES ('Clientes', 'clientes', 'fa-solid fa-users', '/clientes', 14, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name), icon_class = VALUES(icon_class), route_prefix = VALUES(route_prefix), sort_order = VALUES(sort_order), is_enabled = VALUES(is_enabled);

-- Migração inicial de síndicos e administradoras do legado para clientes
INSERT INTO client_entities (
    entity_type, profile_scope, role_tag, display_name, legal_name, cpf_cnpj, phones_json, emails_json, notes, is_active, created_by, updated_by
)
SELECT
    CASE WHEN COALESCE(a.cnpj, '') <> '' THEN 'pj' ELSE 'pf' END AS entity_type,
    'contato' AS profile_scope,
    CASE WHEN a.type = 'sindico' THEN 'sindico' ELSE 'administradora' END AS role_tag,
    a.nome AS display_name,
    a.nome AS legal_name,
    a.cnpj AS cpf_cnpj,
    JSON_ARRAY(JSON_OBJECT('label','Principal','number',COALESCE(a.telefone,''))) AS phones_json,
    JSON_ARRAY(JSON_OBJECT('label','Principal','email',COALESCE(a.email,''))) AS emails_json,
    a.observacoes,
    1,
    1,
    1
FROM administradoras a
WHERE NOT EXISTS (
    SELECT 1 FROM client_entities ce WHERE ce.display_name = a.nome AND ce.role_tag = CASE WHEN a.type = 'sindico' THEN 'sindico' ELSE 'administradora' END
);
