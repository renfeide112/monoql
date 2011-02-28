Ext.ns('monoql.tab');
monoql.tab.hometab = function() {
	var cls = 'monoql-tab-hometab';
	var Class = Ext.extend(Ext.Panel, {
		title:'MonoQL',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();