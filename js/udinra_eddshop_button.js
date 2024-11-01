(function () {
    'use strict';

    tinymce.PluginManager.add('udinra_eddshop_subscribe', function (editor, url) {
        editor.addButton('udinra_eddshop_subscribe', {
            title: 'Udinra EddShop Button',
            image: url + '/../image/eddshop.png',

            onclick: function () {
                editor.windowManager.open({
                    title: 'Udinra EddShop Configuration',
                    body: [
                       {
                            type: 'listbox',
                            name: 'sort',
                            label: 'Default Order',
                            values: [
                                {text: 'Best Sellers', value: 'earning'},
                                {text: 'Popularity', value: 'sales'},
                                {text: 'Lowest Price', value: 'lowprice'},
								{text: 'Highest Price', value: 'highprice'},								
                                {text: 'Newest First', value: 'newest'},
                                {text: 'Oldest First', value: 'oldest'}								
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'show',
                            label: 'Show Title',
                            values: [
                                {text: 'Disable', value: 'false'},
                                {text: 'Enable', value: 'true'}
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'image',
                            label: 'Thumnail Size',
                            values: [
                                {text: 'Small', value: 'small'},
                                {text: 'Medium', value: 'medium'}
                            ]
                        }
                    ],
                    onsubmit: function (e) {
                        editor.insertContent('[udinra_eddshop sort="' + e.data.sort 
												+ '" show="' + e.data.show 
												+ '" image="' + e.data.image 												
												+ '" ]' );
                    }
                });
            }
        });
    });
})();


