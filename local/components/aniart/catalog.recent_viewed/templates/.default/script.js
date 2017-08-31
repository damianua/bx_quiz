var AjaxViewedProduct = (function (App) {
	
	'use strict';
	
	window.App = App = App || {};
	
	return {
		ajaxHandler: '/ajax/common.php',
		block: {
			item: '.bx_catalog_item',
			delete_button: '.delete-recent-item',
		},
		_deleteProduct:function(el){
		
		
		},
		events: function () {
			var _app = App,
				_this = this;
			
			$(_this.block.item).on('click', _this.block.delete_button, function () {
				
			     var el_id = $(this).closest(_this.block.item).data("item");
			     var el_dom =$(this).closest(_this.block.item);
			     
			     if(el_id){
				     
				     $.post(_this.ajaxHandler, {
					     handler: 'recentviewed',
					     func: 'deleteItem',
					     productId: el_id
				     }, function(response){
				     	
					     if(response.status == 'ok'){
					     	el_dom.hide();
					     }
				     }, 'json')
			     }
			});
		},
		init: function () {
			var _app = App,
				_this = this;
			
			
			
			_this.events();
		},
		
		
		
	}
})(window.App);

$(document).ready(function () {
	AjaxViewedProduct.init();
});