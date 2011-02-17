Ext.ns('monoql.tab');
monoql.tab.maintabset = function() {
	var cls = 'monoql-tab-maintabset';
	var queryTabCount = 0;
	var Class = Ext.extend(monoql.tab.tabset, {
		headerStyle:'border-top-width:0px;',
		initComponent: function() {
			this.items = [];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.addQueryTab(true);
		},
		addQueryTab:function(connection) {
			if (connection) {
				var tab = new monoql.tab.querytab({
					index:++queryTabCount,
					connection:new monoql.data.connectionrecord({
						host:'localhost',
						type:'mysql',
						username:'test',
						password:'test'
					})
				});
				this.activate(this.add(tab));
				return tab;
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();