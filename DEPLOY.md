# Despliegue Restaurant CRM

## Landing (Vercel o Netlify)

### Vercel
1. Conectar repo en [vercel.com](https://vercel.com)
2. **Root Directory**: dejar vacío (proyecto completo)
3. **Build Command**: `node landing/build.js`
4. **Output Directory**: `landing`
5. **Variable de entorno**: `CRM_URL` = URL del CRM (ej: `https://restaurant-crm.onrender.com/`)
6. Deploy

### Netlify
1. Conectar repo en [netlify.com](https://netlify.com)
2. **Build command**: `node landing/build.js`
3. **Publish directory**: `landing`
4. **Variable de entorno**: `CRM_URL` = URL del CRM
5. Deploy

---

## CRM PHP (Render)

1. Crear **MySQL Database** en Render
2. Crear **Web Service** → Conectar repo
3. **Runtime**: Docker
4. **Environment Variables** (desde la BD creada):
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `APP_ENV` = production
   - `APP_DEBUG` = false
5. Ejecutar el schema: conectar a la BD y ejecutar `database/schema.sql` + `database/seed.sql`
6. Deploy

---

## Orden recomendado
1. Desplegar CRM en Render primero
2. Copiar URL del CRM (ej: `https://restaurant-crm-xxxx.onrender.com`)
3. Desplegar Landing en Vercel/Netlify con `CRM_URL` = esa URL + `/`
