<?php
$thamso = isset($_GET['thamso']) ? $_GET['thamso'] : 'default';
switch ($thamso) {
    case "searchPages":
        include("src/contents/search.php");
        break;
    case "logout":
        include("src/contents/logout.php");
        break;
    case "reSendOTP":
        include("src/contents/reSendOTP.php");
        break;

    case "contactDetails":
        include("src/utils/Contact/contact.php");
        break;

    case "register":
        include("src/contents/Login/register.php");
        break;

    case "login":
        include("src/contents/Login/login.php");
        break;

    case "verification":
        include("src/contents/Login/verification.php");
        break;

    case "forgotpassword":
        include("src/contents/Login/forgotPassword.php");
        break;

    case "aboutUs":
        include("src/utils/Introduce/aboutUs.php");
        break;

    case "manufacturingFactory":
        include("src/utils/Introduce/manufacturingFactory.php");
        break;

    case "privacyPolicy":
        include("src/utils/Introduce/privacyPolicy.php");
        break;

    case "termsAndConditions":
        include("src/utils/Introduce/termsAndConditions.php");
        break;

    case "policy":
        include("src/utils/Introduce/policy.php");
        break;

    case "serviceDetail":
        include("src/contents/carts/checkoutDetail.php");
        break;

    case "news":
        include("src/services/News/news.php");
        break;

    case "newsDetails":
        include("src/services/News/newsDetails.php");
        break;

    case "search":
        include("src/contents/search.php");
        break;

    case "notFound":
        include("src/contents/404.php");
        break;
    case "pages":
        include("src/services/Pages/pages.php");
        break;
    case "pageTypes":
        include("src/services/Pages/pageTypes.php");
        break;
    case "pageDetail":
        include("src/services/Pages/pageDetail.php");
        break;
    //Dashboard
    case "dashboard":
        include("src/admin/config/dashboard.php");
        break;
    case "configuration":
        include("src/admin/config/configuration.php");
        break;
    case "historySystem":
        include("src/admin/config/historySystem.php");
        break;
    case "listNews":
        include("src/admin/news/listNews.php");
        break;
    case "addNews":
        include("src/admin/news/addNews.php");
        break;
    case "editNews":
        include("src/admin/news/editNews.php");
        break;
    case "deleteNews":
        include("src/admin/news/deleteNews.php");
        break;
    case "historyNews":
        include("src/admin/news/historyNews.php");
        break;

    case "listUser":
        include("src/admin/users/listUser.php");
        break;
    case "addUser":
        include("src/admin/users/addUser.php");
        break;
    case "editUser":
        include("src/admin/users/editUser.php");
        break;
    case "deleteUser":
        include("src/admin/users/deleteUser.php");
        break;
    case "historyUser":
        include("src/admin/users/historyUser.php");
        break;

    case "listPage":
        include("src/admin/pages/listPage.php");
        break;
    case "addPage":
        include("src/admin/pages/addPage.php");
        break;
    case "editPage":
        include("src/admin/pages/editPage.php");
        break;
    case "deletePage":
        include("src/admin/pages/deletePage.php");
        break;
    case "historyPage":
        include("src/admin/pages/historyPage.php");
        break;

    case "listService":
        include("src/admin/services/listService.php");
        break;
    case "addService":
        include("src/admin/services/addService.php");
        break;
    case "editService":
        include("src/admin/services/editService.php");
        break;
    case "deleteService":
        include("src/admin/services/deleteService.php");
        break;
    case "historyService":
        include("src/admin/services/historyService.php");
        break;

    case "listPartner":
        include("src/admin/partners/listPartner.php");
        break;
    case "addPartner":
        include("src/admin/partners/addPartner.php");
        break;
    case "editPartner":
        include("src/admin/partners/editPartner.php");
        break;
    case "deletePartner":
        include("src/admin/partners/deletePartner.php");
        break;
    case "historyPartner":
        include("src/admin/partners/historyPartner.php");
        break;

    case "listReview":
        include("src/admin/reviews/listReview.php");
        break;
    case "addReview":
        include("src/admin/reviews/addReview.php");
        break;
    case "editReview":
        include("src/admin/reviews/editReview.php");
        break;
    case "deleteReview":
        include("src/admin/reviews/deleteReview.php");
        break;
    case "historyReview":
        include("src/admin/reviews/historyReview.php");
        break;

    case "listComment":
        include("src/admin/comments/listComment.php");
        break;
    case "addComment":
        include("src/admin/comments/addComment.php");
        break;
    case "editComment":
        include("src/admin/comments/editComment.php");
        break;
    case "deleteComment":
        include("src/admin/comments/deleteComment.php");
        break;
    case "historyComment":
        include("src/admin/comments/historyComment.php");
        break;

    case "listContact":
        include("src/admin/contacts/listContact.php");
        break;
    case "deleteContact":
        include("src/admin/contacts/deleteContact.php");
        break;
    case "historyContact":
        include("src/admin/contacts/historyContact.php");
        break;
    case "viewContact":
        include("src/admin/contacts/viewContact.php");
        break;

    case "listOrder":
        include("src/admin/orders/listOrder.php");
        break;
    case "addOrder":
        include("src/admin/orders/addOrder.php");
        break;
    case "editOrder":
        include("src/admin/orders/editOrder.php");
        break;
    case "deleteOrder":
        include("src/admin/orders/deleteOrder.php");
        break;
    case "historyOrder":
        include("src/admin/orders/historyOrder.php");
        break;


    case "deleteHistory":
        include("src/admin/config/deleteHistory.php");
        break;
    case "deleteAllHistory":
        include("src/admin/config/deleteAllHistory.php");
        break;

    case "previewCustomerPage":
        include("src/admin/config/previewCustomerPage.php");
        break;
    default:
        include('src/contents/content.php');
}
