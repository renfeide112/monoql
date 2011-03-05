Ext.ns('monoql.bar');
monoql.bar.bar = function() {
	var cls = 'monoql-bar-bar';
	var Class = Ext.extend(Ext.Toolbar, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();