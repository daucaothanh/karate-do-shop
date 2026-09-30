# Chương 2: Phân tích và thiết kế hệ thống

## 2.1 Tổng quan về hệ thống

Hệ thống quản lý bán hàng và vận hành thương hiệu Karate Do là một giải pháp phần mềm được xây dựng nhằm hỗ trợ quy trình kinh doanh từ quản lý sản phẩm, khách hàng, đơn hàng, thanh toán cho đến việc theo dõi hoạt động của nhân sự và lịch làm việc. Với xu hướng phát triển mạnh mẽ của thương mại điện tử, doanh nghiệp cần một hệ thống có khả năng lưu trữ, xử lý và thống kê dữ liệu một cách hiệu quả, đồng thời giúp quản trị viên kiểm soát tốt hơn mọi khâu trong chuỗi hoạt động.

Hệ thống này không chỉ tập trung vào việc bán hàng trực tuyến mà còn hỗ trợ các chức năng quản trị nội bộ như phân quyền người dùng, theo dõi đơn hàng, xử lý trạng thái giao dịch và kiểm soát lượng hàng tồn kho. Mỗi chức năng trong hệ thống đều đóng vai trò quan trọng trong việc tối ưu hóa quy trình làm việc, giảm sai sót và nâng cao hiệu quả kinh doanh.

## 2.2 Mục tiêu của hệ thống

Hệ thống được thiết kế với các mục tiêu chính như sau:

- Quản lý thông tin sản phẩm, danh mục và hình ảnh sản phẩm một cách đầy đủ và cập nhật.
- Hỗ trợ khách hàng tìm kiếm, xem chi tiết sản phẩm và thực hiện đặt hàng trực tuyến.
- Quản lý đơn hàng từ lúc đặt hàng, xác nhận, giao hàng đến khi hoàn tất.
- Theo dõi tồn kho, số lượng nhập và số lượng còn lại để đảm bảo nguồn hàng ổn định.
- Tạo ra hệ thống phân quyền người dùng rõ ràng giữa khách hàng, nhân viên và quản trị viên.
- Cung cấp báo cáo và thống kê giúp doanh nghiệp đưa ra quyết định đúng đắn hơn.

## 2.3 Phân tích yêu cầu nghiệp vụ

### 2.3.1 Quy trình khách hàng

Quy trình bắt đầu khi khách hàng truy cập vào website, duyệt sản phẩm theo danh mục hoặc tìm kiếm theo tên, kích thước, màu sắc và mức giá. Sau khi lựa chọn sản phẩm, khách hàng sẽ thêm vào giỏ hàng và tiến hành đặt hàng. Hệ thống sẽ kiểm tra thông tin sản phẩm, tính toán tổng tiền, lưu lại phương thức thanh toán và địa chỉ nhận hàng.

Sau khi đơn hàng được tạo, khách hàng có thể theo dõi trạng thái đơn hàng như chờ xác nhận, đang xử lý, đã giao hoặc đã hoàn thành. Việc này giúp khách hàng chủ động trong việc kiểm tra tiến độ đặt hàng và nâng cao trải nghiệm mua sắm.

### 2.3.2 Quy trình quản lý sản phẩm

Người quản trị có trách nhiệm quản lý thông tin sản phẩm như tên, mô tả, giá bán, danh mục, số lượng tồn kho, màu sắc, kích thước và hình ảnh. Các sản phẩm có thể thuộc nhiều danh mục khác nhau và tùy vào từng sản phẩm, hệ thống có thể lưu trữ thông tin về số lượng tồn kho theo từng kích cỡ hoặc theo tổng số lượng chung.

Việc quản lý số lượng nhập sản phẩm là một phần rất quan trọng trong hệ thống. Dữ liệu về số lượng nhập giúp doanh nghiệp tính toán tồn kho, dự đoán nhu cầu tiêu thụ và điều chỉnh kế hoạch nhập hàng phù hợp. Đây là yếu tố đảm bảo hệ thống luôn hoạt động ổn định và giảm thiểu rủi ro thiếu hàng hoặc thừa hàng.

### 2.3.3 Quy trình quản lý đơn hàng

Đơn hàng được tạo bởi khách hàng sẽ được hệ thống lưu trữ với các thông tin như người đặt hàng, tổng giá trị hóa đơn, trạng thái hiện tại, địa chỉ giao nhận và người xử lý. Trong quá trình vận hành, nhân viên hoặc quản trị viên có thể cập nhật trạng thái đơn hàng theo từng giai đoạn xử lý.

Quy trình này giúp tăng tính minh bạch trong hoạt động kinh doanh và tạo điều kiện cho quản lý kiểm soát quá trình xử lý đơn hàng một cách hiệu quả. Hệ thống cũng hỗ trợ lưu trữ lịch sử trạng thái đơn hàng, giúp người quản trị dễ dàng kiểm tra nguyên nhân hoặc tiến độ xử lý từng đơn.

### 2.3.4 Quy trình quản lý người dùng và phân quyền

Hệ thống cho phép phân loại người dùng thành khách hàng, nhân viên và quản trị viên. Mỗi nhóm tài khoản có quyền truy cập và thực hiện chức năng khác nhau. Khách hàng chủ yếu thực hiện các thao tác như xem sản phẩm, đặt hàng và theo dõi giao dịch. Nhân viên có thể xử lý đơn hàng và quản lý các hoạt động vận hành. Quản trị viên có toàn quyền kiểm soát hệ thống, bao gồm phần quản lý sản phẩm, người dùng, đơn hàng và báo cáo.

Việc phân quyền giúp tăng tính bảo mật cho hệ thống, ngăn chặn quyền truy cập trái phép và đảm bảo dữ liệu được quản lý đúng người, đúng mục đích.

## 2.4 Phân tích các chức năng chính của hệ thống

### 2.4.1 Quản lý tài khoản người dùng

Chức năng này cho phép người dùng đăng nhập, đăng ký tài khoản, cập nhật thông tin cá nhân, thay đổi mật khẩu và theo dõi lịch sử hoạt động. Hệ thống lưu trữ thông tin như tên, email, số điện thoại, địa chỉ, vai trò và trạng thái tài khoản.

### 2.4.2 Quản lý sản phẩm và danh mục

Module sản phẩm bao gồm các thao tác cơ bản như:

- Thêm, sửa, xóa sản phẩm
- Quản lý danh mục sản phẩm
- Cập nhật hình ảnh, mô tả, giá và số lượng tồn kho
- Quản lý kích thước, màu sắc và số lượng theo từng phân loại

Nhờ đó, doanh nghiệp dễ dàng cập nhật hàng hóa và kiểm soát dữ liệu theo thời gian thực.

### 2.4.3 Quản lý giỏ hàng và đơn hàng

Khách hàng có thể thêm sản phẩm vào giỏ hàng, điều chỉnh số lượng, xóa sản phẩm và tiếp tục thanh toán. Hệ thống sẽ tự động tính tổng tiền, kiểm tra đơn hàng và lưu trữ thông tin giao dịch. Quản trị viên và nhân viên có thể xem danh sách đơn hàng, cập nhật trạng thái và theo dõi tiến độ xử lý.

### 2.4.4 Quản lý thanh toán và địa chỉ giao hàng

Hệ thống lưu trữ thông tin thanh toán và địa chỉ giao hàng của khách hàng để phục vụ cho quá trình mua hàng và hỗ trợ sau bán hàng. Việc có dữ liệu địa chỉ rõ ràng giúp cải thiện độ chính xác trong quá trình vận chuyển và giảm nguy cơ sai sót khi giao hàng.

### 2.4.5 Quản lý nhân sự và ca làm việc

Với mô hình doanh nghiệp có hoạt động bán hàng và vận hành nội bộ, hệ thống cần quản lý thông tin nhân viên, ca làm việc, lịch trực và quyền duyệt đơn hàng. Đây là một chức năng quan trọng để đảm bảo nhân viên làm việc đúng ca, dễ dàng theo dõi tiến độ và kiểm soát hoạt động trong giờ làm việc.

## 2.5 Phân tích dữ liệu và mô hình thực thể

Hệ thống vận hành trên nền tảng dữ liệu có các thực thể chính như sau:

- Người dùng: chứa thông tin tài khoản, vai trò, trạng thái hoạt động và quyền truy cập.
- Sản phẩm: lưu trữ thông tin sản phẩm, giá bán, danh mục, tồn kho và hình ảnh.
- Danh mục sản phẩm: dùng để phân loại sản phẩm theo nhóm phù hợp.
- Đơn hàng: lưu trữ thông tin giao dịch, thời gian đặt hàng, tổng tiền và trạng thái xử lý.
- Chi tiết đơn hàng: lưu thông tin từng sản phẩm trong đơn hàng, số lượng và giá trị tương ứng.
- Địa chỉ giao hàng: lưu trữ thông tin nơi nhận hàng của khách hàng.
- Thanh toán: lưu trữ phương thức thanh toán và trạng thái giao dịch.
- Nhân sự và ca làm việc: lưu lịch trực, ca làm việc và quy định quyền xử lý của từng nhân viên.

Những thực thể này có mối quan hệ chặt chẽ với nhau. Ví dụ, một đơn hàng thuộc về một khách hàng, chứa nhiều chi tiết đơn hàng; mỗi chi tiết đơn hàng lại liên quan đến một sản phẩm cụ thể. Cùng với đó, thông tin tồn kho của sản phẩm được cập nhật theo từng lần nhập hàng hoặc bán hàng, tạo nên cơ sở dữ liệu hỗ trợ cho quá trình quản lý và thống kê hiệu quả.

## 2.6 Đánh giá tính cần thiết của hệ thống

Việc xây dựng hệ thống quản lý bán hàng cho thương hiệu Karate Do là cần thiết trong bối cảnh doanh nghiệp cần tăng hiệu quả quản lý, giảm sai sót trong quy trình kinh doanh và nâng cao trải nghiệm khách hàng. Nếu hệ thống được triển khai đúng cách, doanh nghiệp sẽ dễ dàng kiểm soát hàng hóa, theo dõi khách hàng, quản lý đơn hàng và tối ưu hóa hoạt động bán hàng.

Ngoài ra, hệ thống còn giúp quản trị viên có cái nhìn tổng quan về doanh thu, hoạt động nhân sự và tình hình tồn kho. Điều này là nền tảng quan trọng để doanh nghiệp phát triển ổn định, mở rộng thị trường và nâng cao tính cạnh tranh trong thời đại thương mại điện tử.

## 2.7 Kết luận

Chương 2 đã phân tích các yêu cầu nghiệp vụ, chức năng chuyên biệt và mô hình dữ liệu của hệ thống quản lý bán hàng Karate Do. Từ đó có thể thấy hệ thống không chỉ là một website bán hàng đơn thuần mà còn là một giải pháp quản lý toàn diện, tích hợp nhiều chức năng phục vụ cho cả khách hàng lẫn quản trị viên.

Những phân tích này là cơ sở để tiến hành thiết kế hệ thống ở mức chi tiết hơn trong các chương tiếp theo, bao gồm thiết kế cơ sở dữ liệu, kiến trúc phần mềm, giao diện người dùng và quy trình triển khai ứng dụng. Đây là bước quan trọng để đảm bảo hệ thống phát triển đúng hướng, đáp ứng yêu cầu thực tế và mang lại hiệu quả kinh doanh cao.
