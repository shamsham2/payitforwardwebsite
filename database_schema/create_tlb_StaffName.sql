CREATE TABLE `PIFVoucherRecords`.`tbl_StaffName` (
  `StaffID` VARCHAR(30) NOT NULL,
  `StaffPassword` VARCHAR(45) NOT NULL,
  `StaffName` VARCHAR(45) NOT NULL,
  `DateAdded` DATE NULL,
  `DateRemoved` DATE NULL,
  PRIMARY KEY (`StaffID`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
