CREATE TABLE `PIFVoucherRecords`.`tbl_VoucherRecord` (
  `VoucherNumber` VARCHAR(45) NOT NULL,
  `ShopName` VARCHAR(45) NOT NULL,
  `VoucherValue` DECIMAL(13,2) NOT NULL,
  `TimeSold` DATETIME NOT NULL,
  `StaffIDforSale` VARCHAR(45) NOT NULL,
  `TimeRedeemed` DATETIME NULL,
  `StaffIDforRdmptn` VARCHAR(45) NULL,
  `ValueRedeemed` DECIMAL(13,2) NULL,
  `ValueNotRedeemed` DECIMAL(13,2) GENERATED ALWAYS AS (VoucherValue-ValueRedeemed),
  PRIMARY KEY (`VoucherNumber`),
  CONSTRAINT fkStaffIDforSale FOREIGN KEY (StaffIDforSale) REFERENCES tbl_StaffName (StaffID) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fkStaffIDforRdmptn FOREIGN KEY (StaffIDforRdmptn) REFERENCES tbl_StaffName (StaffID) ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;