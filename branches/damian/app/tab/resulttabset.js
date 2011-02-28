Ext.ns('monoql.tab');
monoql.tab.resulttabset = function() {
	var cls = 'monoql-tab-resulttabset';
	var Class = Ext.extend(monoql.tab.tabset, {
		activeTab:0,
		border:false,
		deferredRender:false,
		constructor:function(config) {
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.resulttab = new monoql.tab.resulttab({tabset:this});
			this.messagetab = new monoql.tab.messagetab({tabset:this});
			this.tab.queryform.on('query', this.onQueryFormQuery, this);
			this.items = [this.resulttab, this.messagetab];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryFormQuery:function(queryform, query, connection) {
			this.activate(this.resulttab);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();