(function ($) {
    "use strict";

    var WidgetWishlistIconHandler = function ($scope, $) {
        var $myBadge = $scope.find('.rmt-wishlist-count');

        // دالة التحديث: تبحث عن الرقم في الصفحة وتحدث أيقونتنا
        var syncCount = function() {
            // قراءة العداد من HTML الثيم (الأدق للثيم)
            var $themeCounter = $('.mini-item-counter');
            if ($themeCounter.length) {
                // التعديل هنا: نأخذ أول واحد فقط ونحوله لرقم
                // .first() -> عشان لو فيه نسختين ميكتبش 11
                var rawText = $themeCounter.first().text().trim(); 
                var count = parseInt(rawText);
                updateUI(count);
            }
        };

        var updateUI = function(count) {
            if (!isNaN(count) && count > 0) {
                $myBadge.text(count).css('display', 'flex');
            } else {
                $myBadge.hide();
            }
        };

        // --- التنفيذ الذكي (بدون Loops) ---
        
        // 1. عند التحميل
        syncCount();

        // 2. الاستماع لأحداث Ajax فقط (بدون مراقبة DOM)
        // هذا هو الحدث الذي يطلقه ووردبريس عند انتهاء أي ريكوست (مثل إضافة للمفضلة)
        $(document).ajaxComplete(function(event, xhr, settings) {
            // نتأكد إن الريكوست خاص بالمفضلة عشان منحدثش عالفاضي
            if ( settings.data && (settings.data.indexOf('wishlist') !== -1 || settings.data.indexOf('add_to_cart') !== -1) ) {
                setTimeout(syncCount, 100); // تحديث بعد لحظة
            }
        });

        // 3. دعم خاص لـ Martfury (هو بيستخدم حدث اسمه 'added_to_wishlist')
        $('body').on('added_to_wishlist removed_from_wishlist', function() {
            setTimeout(syncCount, 100);
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wishlist-icon.default', WidgetWishlistIconHandler);
    });

})(jQuery);