/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Ngôn ngữ giao diện của CKEditor
    config.language = 'en';

    // Màu giao diện của CKEditor
    config.uiColor = '#9AB8F3';

    // Chiều cao của trình chỉnh sửa
    config.height = 350;

    // Chiều rộng của trình chỉnh sửa (auto để tự động theo kích thước chứa)
    config.width = 'auto';

    // Cho phép thanh công cụ thu gọn
    config.toolbarCanCollapse = true;

    // Cấu hình đường dẫn cho CKFinder để duyệt và tải hình ảnh
    config.filebrowserImageBrowseUrl = '/src/library/ckfinder/ckfinder.html?Type=Images';
    config.filebrowserFlashBrowseUrl = '/src/library/ckfinder/ckfinder.html?Type=Flash';

    // Cấu hình đường dẫn cho CKFinder để tải lên hình ảnh và flash
    config.filebrowserImageUploadUrl = '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

    // Cho phép tất cả các nội dung HTML mà không thay đổi hoặc loại bỏ chúng
    config.allowedContent = true;

    // Các cài đặt bổ sung tùy chọn khác (nếu cần)
    // config.extraAllowedContent = 'img[alt,!src,width,height]';  // Cho phép thêm các thuộc tính cụ thể
};
