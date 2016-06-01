{
	btnReclamar: true,
	gruposBarraTareasDocumento: [{name:"legales",title:"Doc. Legales",grupo:1,height:0},
                            {name:"proceso",title:"Doc del Proceso",grupo:0,height:0}],
	estadoReclamar: "pendiente_asignacion",
    constructor: function(config){
    	
		if (config.estado != "borrador") {
			this.rowExpander= new Ext.ux.grid.RowExpander({
			        tpl : new Ext.Template(
			            "<br>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Objeto:&nbsp;&nbsp;</b> {objeto}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Solicitud:&nbsp;&nbsp;</b> {solicitud}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Inicio:&nbsp;&nbsp;</b> {fecha_inicio:date(\"d/m/Y\")}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Fin\":&nbsp;&nbsp;</b> {fecha_fin:date(\"d/m/Y\")}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Monto:&nbsp;&nbsp;</b> {monto}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Moneda:&nbsp;&nbsp;</b> {moneda}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Conceptos:&nbsp;&nbsp;</b> {desc_ingas}</p>",
			            "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Ordenes de Trabajo:&nbsp;&nbsp;</b> {desc_ot}</p><br>"
			        )
		    });
    
		    this.arrayDefaultColumHidden=	["estado","fecha_mod","usr_reg","usr_mod","estado_reg","fecha_reg","objeto","fecha_inicio",
						"fecha_fin","id_gestion","id_persona","id_institucion","observaciones","solicitud","monto","id_moneda",
						"fecha_elaboracion","plazo","tipo_plazo","id_cotizacion","periodicidad_pago","tiene_retencion","modo",
						"id_contrato_fk","id_concepto_ingas","id_orden_trabajo","cargo","lugar","forma_contratacion","modalidad",
						"representante_legal","rpc","mae"];
						
		}
		Phx.vista[config.clase_generada].superclass.constructor.call(this,config);
		if (this.config.estado == "borrador") {
		  this.construyeVariablesContratos();
		}

		if (this.config.estado == "finalizado") {
		  this.getBoton("sig_estado").setDisabled(true);
		}
		this.getBoton("btnReclamar").hide();
		if(this.config.estado == this.estadoReclamar){
			this.getBoton("btnReclamar").show();
		}
		
		//Definición de evento para mostrar/ocultar componentes en función del tipo de contratos
		//this.Cmp.tipo.on("select", function(c,r,i){
		//		if(this.Cmp.tipo.getValue() == "administrativo"){
		//			alert("administrativo");
		//		} else if(this.Cmp.tipo.getValue() == "administrativo_alquiler"){
		//			alert("administrativo_alquiler");
		//		} else if (this.Cmp.tipo.getValue() == "administrativo_internacional"){
		//			alert("administrativo_internacional");
		//		}
		//	},
		//this);
		
    },
    construyeVariablesContratos: function(){
		Phx.CP.loadingShow();
		Ext.Ajax.request({
                url: "../../sis_workflow/control/Tabla/cargarDatosTablaProceso",
                params: { "tipo_proceso": "CON", "tipo_estado":"finalizado" , "limit":"100","start":"0"},
                success: this.successCotratos,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope:   this
        });
    },
    successCotratos: function(resp){
        Phx.CP.loadingHide();
        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
	    if(reg.datos){
			this.ID_CONT = reg.datos[0].atributos.id_tabla
console.log(this.Cmp);
			this.Cmp.id_contrato_fk.store.baseParams.id_tabla = this.ID_CONT;
		 }else{
			alert("Error al cargar datos de contratos")
		}
     },
    agregarArgsExtraSubmit: function(){
		if (this.config.estado == "borrador") {			
		   if (this.Cmp.id_contrato_fk.getValue() == "" || this.Cmp.id_contrato_fk.getValue() == null || this.Cmp.id_contrato_fk.getValue() == undefined) {
			   delete this.argumentExtraSubmit.nro_tramite;
			 } else {
			   var recContrato = this.Cmp.id_contrato_fk.store.getAt(this.Cmp.id_contrato_fk.getValue());
			   this.argumentExtraSubmit.nro_tramite = recContrato.data.nro_tramite;
		   }
		}
    },
    iniciarEventos: function(){

        if (this.config.estado == "registro") {
			this.Cmp.tipo_plazo.on("select",function(c,r,i){
				if (this.Cmp.tipo_plazo.getValue() == "fecha_fija") {
					this.mostrarComponente(this.Cmp.fecha_fin);
					this.Cmp.fecha_fin.reset();
					this.Cmp.fecha_fin.allowBlank = false;
					this.ocultarComponente(this.Cmp.plazo);
					this.Cmp.plazo.reset();
					this.Cmp.plazo.allowBlank = true;
				} else if (this.Cmp.tipo_plazo.getValue() == "tiempo_indefinido") {
					this.ocultarComponente(this.Cmp.fecha_fin);
					this.Cmp.fecha_fin.reset();
					this.Cmp.fecha_fin.allowBlank = true;
					this.ocultarComponente(this.Cmp.plazo);
					this.Cmp.plazo.reset();
					this.Cmp.plazo.allowBlank = true;
				} else {
					this.ocultarComponente(this.Cmp.fecha_fin);
					this.Cmp.fecha_fin.reset();
					this.Cmp.fecha_fin.allowBlank = true;
					this.mostrarComponente(this.Cmp.plazo);
					this.Cmp.plazo.reset();
					this.Cmp.plazo.allowBlank = false;
				}


			},this);
		} else if (this.config.estado == "borrador") {
			this.Cmp.modo.on("select",function(c,r,i){
				if (this.Cmp.modo.getValue() == "adenda") {
					this.mostrarComponente(this.Cmp.id_contrato_fk);
					this.Cmp.id_contrato_fk.reset();
					this.Cmp.id_contrato_fk.allowBlank = false;
				} else {
					this.ocultarComponente(this.Cmp.id_contrato_fk);
					this.Cmp.id_contrato_fk.reset();
					this.Cmp.id_contrato_fk.allowBlank = true;
				}

			},this);

		}
   },
   onSubmit: function(o, x, force) {
   		var error = false;
   		if (this.Cmp.fecha_fin) {
   			if (this.Cmp.fecha_fin.getValue()) {
   				if (this.Cmp.fecha_fin.getValue() < this.Cmp.fecha_inicio.getValue()) {
   					error = true;
   				}	
   			}
   		}
   		if (error) {
   			alert("La fecha de finalización del contrato no puede ser menor a la fecha de inicio");
   		} else {
   			Phx.vista[clase_generada].superclass.onSubmit.call(this,o, x, force);
   		}
   }
}

