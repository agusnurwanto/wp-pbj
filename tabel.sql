CREATE TABLE `data_anggota_panitia` (
  `id` int(11) NOT NULL,
  `pnt_id` numeric(19,0) NOT NULL,
  `audittype` varchar(1) NOT NULL,
  `audituser` text NOT NULL,
  `auditupdate` datetime NOT NULL,
  `agp_jabatan` varchar(1) NOT NULL,
  `peg_id` numeric(19,0) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `data_anggota_panitia`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `data_anggota_panitia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `data_panitia` (
  `id` int(11) NOT NULL,
  `pnt_id` numeric(19,0) NOT NULL,
  `stk_id` numeric(19,0) NOT NULL,
  `audittype` varchar(1) NOT NULL,
  `audituser` text NOT NULL,
  `auditupdate` datetime NOT NULL,
  `pnt_nama` text NOT NULL,
  `pnt_tahun` year(4) NOT NULL,
  `pnt_no_sk` text DEFAULT NULL,
  `agc_id` numeric(19,0) DEFAULT NULL,
  `pnt_alamat` text DEFAULT NULL,
  `pnt_email` text DEFAULT NULL,
  `pnt_fax` text DEFAULT NULL,
  `pnt_telp` text DEFAULT NULL,
  `pnt_website` text DEFAULT NULL,
  `kbp_id` bigint DEFAULT NULL,
  `ukpbj_id` double(18,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `id_ketua_ukpbj` double(18,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `data_panitia`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `data_panitia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;