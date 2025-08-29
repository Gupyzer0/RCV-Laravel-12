# Sistema de RCV
# RCV-Laravel-12

x Instalar Laravel 12
x Instalar/Configurar Laravelui con bootstrap 4
x Instalar Laravel Spatie
x Migraciones de Roles para Laravel Spatie
Copiar Archivos desde proyecto de RCV. . .

x - Controladores
x - Servicios
x - Rutas Nuevas
x - public

Modificar codigo para nueva logica de roles
x A donde redirecciona el login según el rol ó en su defecto cambiar el dashboard según el rol.
x Unificar vista de layout y así poder colocar que enlaces son visibles segun rol
x Determinar que se hara con el rol de "moderador" osea, como unirlo al rol de 'administrador'. 
  -> si hay un rol de moderador.
x Ordenar las rutas en distintos archivos para mejor gestión
x Corrigiendo calculo erroneo de comisiones, ahora muestra comisiones al supervisor.
- Ruta de facturacion nueva
- Eliminar todo lo relacionado con las oficinas . . .  eso no va. De ser necesario solo debe asociarse
  los usuarios con una oficina en el futuro . . .
- Extraño filtro automatico en admin/reporte general
- Estadisticas: tienen algun scope por type?
- Acomodar administracion de usuarios (implementar nueva logica de roles)
  - Implementar logica de permisos sobre las polizas.
  - Las rutas de modificar la informacion del perfil del usuario podrian ser alterables cambiando el id
    lo que es una vulnerabilidad grave . . .
- Implementar nuevamente formulario para realizar polizas ...
- Ordenar controladores.
- Dependencias de modelos
- Relaciones
- Vistas de login
- Map -> WTF !?

Reconfigurar funcionamiento de LogsActivity en los modelos ... Parece que es necesario implementar
un metodo específico en esta nueva version


--------------------------------------------
- Calculo de comisiones


-> Profit del usuario
-> Profit del moderador


