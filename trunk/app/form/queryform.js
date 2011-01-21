Ext.ns('monoql.form');
monoql.form.queryform = function() {
	var cls = 'monoql-form-queryform';
	var Class = Ext.extend(monoql.form.form, {
		bodyStyle:{
			'border-top-width':'0px',
			'border-left-width':'0px',
			'border-right-width':'0px'
		},
		initComponent: function() {
			this.querytextarea = new Ext.form.TextArea({
				hideLabel:true,
				anchor:'0 0',
				style:{
					'border-width':'0px'
				}
			});
			this.items = [this.querytextarea];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();