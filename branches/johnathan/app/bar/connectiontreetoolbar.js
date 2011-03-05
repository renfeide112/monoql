Ext.ns('monoql.bar');
monoql.bar.connectiontreetoolbar = function() {
	var cls = 'monoql-bar-connectiontreetoolbar';	
	var Class = Ext.extend(monoql.bar.toolbar, {
		style:'border-left-width:0px;border-top-width:0px;',
		initComponent: function() {
			this.newconnectionbutton = new monoql.button.button({
				text:'New Connection',
				iconCls:cls + '-newconnectionbutton-icon',
				enableToggle:true
			});
			this.refreshbutton = new monoql.button.button({
				text:'',
				disabled:true,
				iconCls:cls + '-refreshbutton-icon',
				onConnectionTreeSelectionChange:function(selModel, node) {
					this.setDisabled(!selModel.getSelectedNode());
				}
			});
			this.newconnectionbutton.on('toggle', this.onNewConnectionButtonToggle, this);
			this.on('render', this.onConnectionTreeToolBarRender, this);
			this.items = [this.newconnectionbutton, '->', this.refreshbutton];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionTreeToolBarRender:function(toolbar) {
			Ext.getCmp('viewport').connectionform.on('hide', this.onUiConnectionFormHide, this);
		},
		onUiConnectionFormHide:function(form) {
			this.newconnectionbutton.toggle(false);
		},
		onNewConnectionButtonToggle:function(button, pressed) {
			if (pressed) {
				if (!ui.connectionform.rendered) {
					ui.connectionform.render(Ext.getBody());
				}
				ui.connectionform.show().el.anchorTo(button.el, 'tl-bl');
			} else {
				ui.connectionform.hide();
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();