Ext.ns('monoql.data');
monoql.data.resultgridstore = function() {
	var Class = Ext.extend(Ext.data.Store, {
		constructor:function(config) {
			Ext.apply(this, {
				autoDestroy:true,
				autoLoad:false,
				proxy:new monoql.data.resultgridproxy({store:this}),
				reader:new monoql.data.resultgridreader({store:this}),
				baseParams:{args:{}}
			});
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();