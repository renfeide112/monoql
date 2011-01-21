Ext.ns('monoql.bar');
monoql.bar.connectiontreetoolbar = function() {
	var cls = 'monoql-bar-connectiontreetoolbar';
	var Class = Ext.extend(monoql.bar.toolbar, {
		style:'border-left-width:0px;',
		initComponent: function() {
			this.newconnectionbutton = new monoql.button.button({
				text:'New Connection',
				iconCls:'monoql-tree-connectiontreetoolbar-newconnectionbutton-icon',
				enableToggle:true
			});
			this.refreshbutton = new monoql.button.button({
				text:'',
				disabled:true,
				iconCls:'monoql-tree-connectiontreetoolbar-refreshbutton-icon',
				onConnectionTreeSelectionChange:function(selModel, node) {
					this.setDisabled(!selModel.getSelectedNode());
				}
			});
			this.items = [this.newconnectionbutton, '->', this.refreshbutton];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();