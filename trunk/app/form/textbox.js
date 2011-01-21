Ext.ns('monoql.form');
monoql.form.textbox = function() {
	var cls = 'monoql-form-textbox';
	var Class = Ext.extend(Ext.form.TextField, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();