Ext.ns('monoql.data');
monoql.data.resultgridstore = function() {
	var Class = Ext.extend(Ext.data.Store, {
		constructor:function(config) {
			config = Ext.apply({
				autoDestroy:true,
				autoLoad:false,
				proxy:new monoql.data.resultgridproxy({store:this}),
				reader:new monoql.data.resultgridreader({store:this}),
				baseParams:{
					args:{},
					start:0,
					limit:100
				}
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();