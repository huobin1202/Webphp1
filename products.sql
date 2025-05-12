-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 06:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admindoan`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `tensp` varchar(255) NOT NULL,
  `dongsp` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `giaban` int(11) NOT NULL,
  `thongtinsp` text DEFAULT NULL,
  `thongsokt` text DEFAULT NULL,
  `hinhanh` varchar(255) NOT NULL DEFAULT '',
  `hinhanh2` varchar(255) NOT NULL DEFAULT '',
  `hinhanh3` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `tensp`, `dongsp`, `status`, `giaban`, `thongtinsp`, `thongsokt`, `hinhanh`, `hinhanh2`, `hinhanh3`) VALUES
(1, 'KLX®230R', 'Dòng KLX', '1', 130000000, 'Niềm vui trọn vẹn của xe đạp địa hình KLX®230R có nghĩa là đã đến lúc Ra ngoài và Vui chơi. Chinh phục địa hình với khả năng xử lý nhanh nhẹn và sức mạnh dồi dào, đồng thời làm tung một số bụi bẩn nghiêm trọng trên đường đi. Hệ thống treo hành trình dài kết hợp với khả năng kiểm soát tốc độ thấp được cải thiện mang đến cho chiếc xe đạp này cảm giác thể thao nổi bật so với những chiếc còn lại. Được hoàn thiện với kiểu dáng lấy cảm hứng từ dòng sản phẩm KX™, gói gọn nhẹ, có khả năng cao của KLX230R mang đến một liều lượng lớn niềm vui cho mọi chuyến đi địa hình.', '🛠️ Động cơ & Truyền động\r\n\r\n    Loại động cơ: 4 thì, xi-lanh đơn, SOHC, làm mát bằng không khí\r\n\r\n    Dung tích: 233 cc\r\n\r\n    Đường kính x Hành trình piston: 67.0 x 66.0 mm\r\n\r\n    Tỷ số nén: 9.4:1\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử DFI® với bướm ga Keihin 32 mm\r\n\r\n    Khởi động: Điện\r\n\r\n    Hộp số: 6 cấp, ly hợp ướt, côn tay\r\n\r\n🏍️ Khung sườn & Hệ thống treo\r\n\r\n    Loại khung: Thép cường lực cao, dạng hộp perimeter\r\n\r\n    Góc nghiêng / Độ trượt: 25.4° / 4.2 in\r\n\r\n    Hệ thống treo trước: Phuộc ống lồng 37 mm, hành trình 9.8 in\r\n\r\n    Hệ thống treo sau: Liên kết Uni-Trak® với giảm xóc đơn, điều chỉnh tải trước liên tục, hành trình 9.8 in​\r\n\r\n🛞 Bánh xe & Phanh\r\n\r\n    Lốp trước: 80/100-21\r\n\r\n    Lốp sau: 100/100-18\r\n\r\n    Phanh trước: Đĩa petal 240 mm với kẹp phanh hai piston\r\n\r\n    Phanh sau: Đĩa petal 220 mm với kẹp phanh một piston​\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Chiều dài tổng thể: 80.5 in\r\n\r\n    Chiều rộng tổng thể: 33.3 in\r\n\r\n    Chiều cao tổng thể: 47.2 in\r\n\r\n    Chiều dài cơ sở: 54.1 in\r\n\r\n    Chiều cao yên: 35.6 in\r\n\r\n    Khoảng sáng gầm: 11.4 in\r\n\r\n    Trọng lượng ướt: 262.4 lb (phiên bản tiêu chuẩn) / 264.5 lb (phiên bản 50 bang)\r\n\r\n    Dung tích bình xăng: 2.0 gal​\r\n\r\n🔧 Tính năng nổi bật\r\n\r\n    Cân bằng sơ cấp: Trục cân bằng sơ cấp giúp giảm rung động, mang lại trải nghiệm lái mượt mà hơn\r\n\r\n    Thiết kế mới: Khung sườn và bình xăng được thiết kế lại để cải thiện khả năng điều khiển và tầm hoạt động\r\n\r\n    Yên xe cải tiến: Yên xe dày hơn và rộng hơn, tăng cường sự thoải mái cho người lái\r\n\r\n    Phong cách KX™: Thiết kế lấy cảm hứng từ dòng xe đua KX™, mang lại vẻ ngoài thể thao và năng động', 'uploads/KLX230R.jpg', 'uploads/KLX230R2.jpg', 'uploads/KLX230R3.jpg'),
(2, 'Z650RS AB', 'Dòng Z', '1', 233000000, '123', '321', 'uploads/z650rsabs.jpg', 'uploads/z650rsabs2.jpg', 'uploads/z650rsabs3.jpg'),
(3, 'NINJA 500', 'Dòng Ninja', '1', 194000000, 'Hãy khẳng định phong cách của bạn cùng chiếc sportbike Ninja® 500. Thu hút mọi ánh nhìn với thiết kế táo bạo đặc trưng của dòng Ninja® và tạo dấu ấn riêng với động cơ 2 xi-lanh 451cc. Trải nghiệm hiệu suất huyền thoại của Ninja trên mẫu sportbike được thiết kế để dẫn đầu mọi đám đông.', '🏍️ Động cơ & Hiệu suất\r\n\r\n    Loại động cơ: DOHC, 4 thì, 2 xi-lanh thẳng hàng, làm mát bằng dung dịch\r\n\r\n    Dung tích xi-lanh: 451 cc\r\n\r\n    Đường kính x Hành trình piston: 70,0 x 58,6 mm\r\n\r\n    Tỷ số nén: 11,3:1\r\n\r\n    Công suất tối đa: 51 mã lực tại 10.000 vòng/phút\r\n\r\n    Mô-men xoắn cực đại: 42,6 Nm tại 6.000 vòng/phút\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử DFI® với hai bướm ga 32 mm\r\n\r\n    Hộp số: 6 cấp\r\n\r\n    Ly hợp: Ly hợp hỗ trợ và chống trượt (Assist & Slipper Clutch)\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Chiều dài x Rộng x Cao: 1.995 x 730 x 1.120 mm\r\n\r\n    Chiều dài cơ sở: 1.375 mm\r\n\r\n    Chiều cao yên: 785 mm\r\n\r\n    Khoảng sáng gầm: 145 mm\r\n\r\n    Dung tích bình xăng: 14 lít\r\n\r\n    Trọng lượng ướt: Khoảng 172 kg\r\n\r\n⚙️ Khung sườn & Hệ thống treo\r\n\r\n    Khung sườn: Thép dạng mắt cáo (trellis)\r\n\r\n    Phuộc trước: Ống lồng 41 mm\r\n\r\n    Giảm xóc sau: Uni-Trak® với khả năng điều chỉnh tải trước\r\n\r\n🛑 Hệ thống phanh & Lốp\r\n\r\n    Phanh trước: Đĩa đơn 310 mm với kẹp phanh 2 piston\r\n\r\n    Phanh sau: Đĩa đơn 220 mm với kẹp phanh 1 piston\r\n\r\n    Hệ thống ABS: Tùy chọn ABS hai kênh\r\n\r\n    Lốp trước: 110/70R17\r\n\r\n    Lốp sau: 150/60R17\r\n\r\n🔌 Trang bị & Công nghệ\r\n\r\n    Màn hình: LCD đơn sắc với kết nối Bluetooth (phiên bản tiêu chuẩn) hoặc màn hình màu TFT (phiên bản SE)\r\n\r\n    Kết nối: Bluetooth với ứng dụng RIDEOLOGY THE APP\r\n\r\n    Hệ thống khởi động thông minh: KIPASS (trên phiên bản SE)\r\n\r\n    Đèn chiếu sáng: Đèn pha và đèn hậu LED', 'uploads/0_15.jpg', 'uploads/top.jpg', 'uploads/meter.jpg'),
(4, 'NINJA 650', 'Dòng Ninja', '1', 210000000, 'Được chế tạo để thể hiện trọn vẹn di sản của dòng xe thể thao Ninja®, Ninja® 650 sở hữu khối động cơ thể thao 649cc, công nghệ tiên tiến và thiết kế sắc sảo. Hiệu suất thể thao không thể nhầm lẫn kết hợp cùng tư thế lái thẳng thoải mái, mang đến những chuyến đi hàng ngày đầy hứng khởi. Bên cạnh đó, phong thái mạnh mẽ của xe luôn gợi nhắc bạn về huyền thoại mà nó kế thừa.', '🏍️ Động cơ & Hiệu suất\r\n\r\n    Loại động cơ: DOHC, 4 thì, 2 xi-lanh thẳng hàng, làm mát bằng dung dịch\r\n\r\n    Dung tích xi-lanh: 649 cc\r\n\r\n    Công suất tối đa: 68 mã lực tại 8.000 vòng/phút\r\n\r\n    Mô-men xoắn cực đại: 65,7 Nm tại 6.500 vòng/phút\r\n\r\n    Hộp số: 6 cấp, côn tay\r\n\r\n    Ly hợp: Ly hợp hỗ trợ và chống trượt (Assist & Slipper Clutch)\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Kích thước (Dài x Rộng x Cao): 2.055 x 740 x 1.145 mm\r\n\r\n    Chiều dài cơ sở: 1.410 mm\r\n\r\n    Chiều cao yên: 790 mm\r\n\r\n    Khoảng sáng gầm: 130 mm\r\n\r\n    Dung tích bình xăng: 15 lít\r\n\r\n    Trọng lượng ướt: 193 kg\r\n\r\n⚙️ Khung sườn & Hệ thống treo\r\n\r\n    Khung sườn: Thép ống dạng mắt cáo (trellis)\r\n\r\n    Phuộc trước: Ống lồng 41 mm\r\n\r\n    Giảm xóc sau: Giảm xóc đơn liên kết ngang (Horizontal Back-link), có thể điều chỉnh tải trước\r\n\r\n🛑 Hệ thống phanh & Lốp\r\n\r\n    Phanh trước: Đĩa đôi 300 mm với kẹp phanh 2 piston\r\n\r\n    Phanh sau: Đĩa đơn 220 mm với kẹp phanh 1 piston\r\n\r\n    Hệ thống ABS: Bosch 9.1M ABS hai kênh\r\n\r\n    Lốp trước: 120/70ZR17\r\n\r\n    Lốp sau: 160/60ZR17\r\n\r\n🔌 Trang bị & Công nghệ\r\n\r\n    Màn hình: Màn hình màu TFT 4,3 inch\r\n\r\n    Kết nối: Bluetooth với ứng dụng RIDEOLOGY THE APP\r\n\r\n    Đèn chiếu sáng: Đèn pha, đèn hậu và đèn định vị LED\r\n\r\n    Tiện ích khác: Chế độ hiển thị ECO giúp tối ưu mức tiêu thụ nhiên liệu', 'uploads/0_152.jpg', 'uploads/top2.jpg', 'uploads/meter2.jpg'),
(5, 'Z H2 SE', 'Dòng Z', '1', 789300000, '', '', 'uploads/ZH2SE.jpg', 'uploads/ZH2SE2.jpg', 'uploads/ZH2SE3.jpg'),
(6, 'NINJA ZX-4R', 'Dòng Ninja', '1', 250000000, 'Ninja® ZX™-4R ABS được trang bị động cơ 4 xi-lanh thẳng hàng 399cc, mang đến hiệu suất hàng đầu phân khúc trong một khung xe nhỏ gọn với kích thước tương tự các mẫu xe dung tích nhỏ hơn. Dù là trên đường đua hay phố thị, hãy trải nghiệm sự phấn khích mà mẫu supersport Ninja ZX-4R ABS mang lại — với sức mạnh chưa từng có, âm thanh rền vang mê hoặc ở vòng tua cao, cùng khả năng xử lý sắc sảo, linh hoạt sẽ đánh thức bản năng thể thao tiềm ẩn trong bạn.', '🏍️ Động cơ & Hiệu suất\r\n\r\n    Loại động cơ: DOHC, 4 thì, 4 xi-lanh thẳng hàng, 16 van, làm mát bằng dung dịch\r\n\r\n    Dung tích xi-lanh: 399 cc\r\n\r\n    Đường kính x Hành trình piston: 57,0 x 39,1 mm\r\n\r\n    Tỷ số nén: 12,3:1\r\n\r\n    Công suất tối đa: 76,4 mã lực tại 15.000 vòng/phút\r\n\r\n    Mô-men xoắn cực đại: 39 Nm tại 13.000 vòng/phút\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử EFI với bướm ga điện tử (ride-by-wire)\r\n\r\n    Hộp số: 6 cấp, côn tay\r\n\r\n    Ly hợp: Ly hợp hỗ trợ và chống trượt (Assist & Slipper Clutch)\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Kích thước (Dài x Rộng x Cao): 1.990 x 765 x 1.110 mm\r\n\r\n    Chiều dài cơ sở: 1.380 mm\r\n\r\n    Chiều cao yên: 800 mm\r\n\r\n    Khoảng sáng gầm: 135 mm\r\n\r\n    Dung tích bình xăng: 15 lít\r\n\r\n    Trọng lượng ướt: 189 kg\r\n\r\n⚙️ Khung sườn & Hệ thống treo\r\n\r\n    Khung sườn: Khung thép dạng mắt cáo (trellis) với độ cứng tối ưu\r\n\r\n    Phuộc trước: Upside-down Showa SFF-BP 37 mm\r\n\r\n    Giảm xóc sau: Giảm xóc đơn với liên kết ngang (Horizontal Back-link), có thể điều chỉnh tải trước\r\n\r\n🛑 Hệ thống phanh & Lốp\r\n\r\n    Phanh trước: Đĩa đôi 290 mm với kẹp phanh 4 piston gắn xuyên tâm\r\n\r\n    Phanh sau: Đĩa đơn 220 mm với kẹp phanh 1 piston\r\n\r\n    Hệ thống ABS: ABS hai kênh của Nissin\r\n\r\n    Lốp trước: 120/70ZR17\r\n\r\n    Lốp sau: 160/60ZR17\r\n\r\n🔌 Trang bị & Công nghệ\r\n\r\n    Hệ thống kiểm soát lực kéo: Kawasaki Traction Control (KTRC)\r\n\r\n    Chế độ lái: Nhiều chế độ lái tùy chọn\r\n\r\n    Màn hình: Màn hình màu TFT với kết nối điện thoại thông minh qua ứng dụng RIDEOLOGY THE APP\r\n\r\n    Đèn chiếu sáng: Đèn pha, đèn hậu và đèn định vị LED', 'uploads/0_153.jpg', 'uploads/top3.jpg', 'uploads/meter3.jpg'),
(7, 'NINJA ZX-10R', 'Dòng Ninja', '1', 765700000, 'Chiếc siêu mô tô thể thao Ninja® ZX™-10R được chế tạo dành cho những người không ngại thử thách. Xuất thân từ trường đua danh tiếng của Giải vô địch thế giới FIM WorldSBK, Ninja ZX-10R là kết quả trực tiếp của hàng thập kỷ đổi mới trong lĩnh vực đua xe đường trường đẳng cấp thế giới, giúp Đội đua Kawasaki (KRT) giành được 6 chức vô địch liên tiếp. Kinh nghiệm từ đường đua đã tạo nên một khối động cơ 4 xi-lanh 998cc mạnh mẽ, khung xe tối ưu cho đua và gói công nghệ điện tử tiên tiến. Hãy vượt qua giới hạn của bản thân cùng Ninja ZX-10R.', '🏍️ Thông số kỹ thuật chính\r\n\r\n    Động cơ: DOHC, 4 xi-lanh thẳng hàng, làm mát bằng dung dịch\r\n\r\n    Dung tích: 998 cm³\r\n\r\n    Đường kính x Hành trình piston: 76,0 x 55,0 mm\r\n\r\n    Tỷ số nén: 13,0:1\r\n\r\n    Công suất cực đại: 203 PS @ 13.200 vòng/phút (213 PS với Ram Air)\r\n\r\n    Mô-men xoắn cực đại: 114,9 Nm @ 11.400 vòng/phút\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử\r\n\r\n    Hộp số: 6 cấp, côn tay\r\n\r\n    Hệ thống khởi động: Khởi động điện\r\n\r\n    Hệ thống bôi trơn: Bôi trơn cưỡng bức\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Dài x Rộng x Cao: 2.085 x 740 x 1.145 mm\r\n\r\n    Chiều dài cơ sở: 1.440 mm\r\n\r\n    Chiều cao yên: 835 mm\r\n\r\n    Khoảng sáng gầm: 135 mm\r\n\r\n    Dung tích bình xăng: 17 lít\r\n\r\n    Trọng lượng ướt: 207 kg\r\n\r\n⚙️ Trang bị & Công nghệ\r\n\r\n    Hệ thống chống bó cứng phanh thông minh (KIBS)\r\n\r\n    Hệ thống kiểm soát lực kéo thể thao (S-KTRC)\r\n\r\n    Hệ thống quản lý khi vào cua (KCMF)\r\n\r\n    Chế độ Launch Control (KLCM)\r\n\r\n    Hỗ trợ sang số nhanh 2 chiều (KQS)\r\n\r\n    Kiểm soát hành trình điện tử\r\n\r\n    Màn hình màu TFT với kết nối điện thoại thông minh qua ứng dụng RIDEOLOGY THE APP\r\n\r\n    Thiết kế khí động học với cánh gió tích hợp\r\n\r\n    Đèn pha, đèn hậu và đèn định vị LED\r\n\r\n🛠️ Hệ thống treo & Phanh\r\n\r\n    Phuộc trước: Showa 43mm Balance Free Fork (BFF)\r\n\r\n    Giảm xóc sau: Showa Balance Free Rear Cushion (BFRC) Lite\r\n\r\n    Phanh trước: Đĩa đôi 330mm với kẹp phanh Brembo M50 4 piston\r\n\r\n    Phanh sau: Đĩa đơn 220mm với kẹp phanh đơn piston', 'uploads/n-zx10r.jpg', 'uploads/n-zx10r2.jpg', 'uploads/n-zx10r3.jpg'),
(8, 'KLX230R', 'Dòng KLX', '1', 148700000, 'KLX230R sẽ đưa những chuyến phiêu lưu của bạn đến tầm cao mới. Với hệ thống treo hành trình dài và khoảng sáng gầm xe lớn, cùng trọng lượng nhẹ, KLX230R – mẫu xe cào cào đích thực – được sinh ra để phục vụ những tay lái mong muốn tìm kiếm niềm vui ở những nơi đầy cát-gió-bùn đất một cách tuyệt vời nhất.', '', 'uploads/KLX230R.jpg', 'uploads/KLX230R2.jpg', 'uploads/KLX230R3.jpg'),
(9, 'Z500 ABS', 'Dòng Z', '1', 170600000, '', '', 'uploads/z500abs.jpg', 'uploads/z500abs2.jpg', 'uploads/z500abs3.jpg'),
(10, 'Z650 ABS', 'Dòng Z', '1', 194000000, '', '', 'uploads/z650abs.jpg', 'uploads/z650abs2.jpg', 'uploads/z650abs3.jpg'),
(11, 'Z900RS', 'Dòng Z', '1', 480800000, '', '', 'uploads/Z900RS.jpg', 'uploads/Z900RS2.jpg', 'uploads/Z900RS3.jpg'),
(12, 'NINJA H2® SX SE', 'Dòng Ninja', '1', 755756100, 'Kawasaki Ninja H2® SX là mẫu sport-tourer tiên tiến nhất từng được Kawasaki chế tạo, đại diện cho sự tích hợp tối thượng giữa công nghệ hiện đại, hiệu suất vượt trội và sự thoải mái khi lái. Trải nghiệm cảm giác phấn khích từ khối động cơ siêu nạp cân bằng độc đáo, phù hợp cho cả những chuyến đi đường dài lẫn di chuyển hằng ngày. Những tính năng chuyên biệt như Hệ thống Hỗ trợ Người lái Nâng cao (ARAS) mang lại lợi ích thực tiễn, bao gồm Kiểm soát hành trình thích ứng (ACC) và Cảnh báo điểm mù (BSD). Tất cả được tích hợp vào bảng đồng hồ màu TFT 6,5 inch cùng hệ thống giải trí Kawasaki SPIN.', '🏍️ Động cơ & Hiệu suất\r\n\r\n    Loại động cơ: 4 thì, 4 xi-lanh thẳng hàng, DOHC, 16 van, làm mát bằng dung dịch\r\n\r\n    Dung tích xi-lanh: 998 cc\r\n\r\n    Đường kính x Hành trình piston: 76,0 x 55,0 mm\r\n\r\n    Tỷ số nén: 11,2:1\r\n\r\n    Công suất tối đa: 207 mã lực tại 10.000 vòng/phút\r\n\r\n    Mô-men xoắn cực đại: 137 Nm tại 8.500 vòng/phút\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử DFI® với bướm ga 40 mm và bộ siêu nạp Kawasaki\r\n\r\n    Hộp số: 6 cấp, sang số kiểu dog-ring\r\n\r\n    Truyền động cuối: Xích kín\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Kích thước (Dài x Rộng x Cao): 2.175 x 790 x 1.260 mm\r\n\r\n    Chiều dài cơ sở: 1.480 mm\r\n\r\n    Chiều cao yên: 835 mm\r\n\r\n    Dung tích bình xăng: 19 lít\r\n\r\n    Trọng lượng ướt: Khoảng 266 kg\r\n\r\n⚙️ Khung sườn & Hệ thống treo\r\n\r\n    Khung sườn: Thép ống dạng mắt cáo (trellis)\r\n\r\n    Phuộc trước: Showa BFF, hành trình ngược 43 mm, có thể điều chỉnh\r\n\r\n    Giảm xóc sau: Showa BFRC-lite, liên kết ngang, có thể điều chỉnh\r\n\r\n🛑 Hệ thống phanh & Lốp\r\n\r\n    Phanh trước: Đĩa đôi 320 mm với kẹp phanh Brembo Stylema 4 piston\r\n\r\n    Phanh sau: Đĩa đơn 250 mm với kẹp phanh 2 piston\r\n\r\n    Hệ thống ABS: Bosch ABS với khả năng kiểm soát lực phanh khi nghiêng\r\n\r\n    Lốp trước: 120/70 ZR17\r\n\r\n    Lốp sau: 190/55 ZR17\r\n\r\n🔌 Trang bị & Công nghệ\r\n\r\n    Hệ thống hỗ trợ người lái nâng cao (ARAS): Bao gồm Kiểm soát hành trình thích ứng (ACC) và Cảnh báo điểm mù (BSD)\r\n\r\n    Màn hình: Màn hình màu TFT 6,5 inch với hệ thống giải trí Kawasaki SPIN\r\n\r\n    Hệ thống treo điện tử: Showa Skyhook (trên phiên bản SE)\r\n\r\n    Các tính năng khác: Kiểm soát lực kéo (KTRC), Hệ thống kiểm soát phanh động cơ (KEBC), Hệ thống sang số nhanh (KQS), Hệ thống kiểm soát khởi động (KLCM), Hệ thống kiểm soát hành trình điện tử, Hệ thống kiểm soát áp suất lốp (TPMS)', 'uploads/n-h2sx1.jpg', 'uploads/n-h2sx2.jpg', 'uploads/n-h2sx3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
