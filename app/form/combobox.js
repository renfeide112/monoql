Ext.ns('monoql.form');
monoql.form.combobox = function() {
	var cls = 'monoql-form-combobox';
	var Class = Ext.extend(Ext.form.ComboBox, {
		initComponent:function() {
			this.addListener('render', this.onComboBoxRender, this, {single:true});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onComboBoxRender:function(combo) {
			combo.el.addListener('focus', this.onComboBoxElementFocus, combo.el, {combo:combo});
		},
		onComboBoxElementFocus:function(event, target, options) {
			if (!options.combo.editable && target.blur) {
				target.blur();
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();