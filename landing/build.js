/**
 * Build script: reemplaza __CRM_URL__ en index.html con la URL del CRM.
 * Usado por Vercel/Netlify. Variable de entorno: CRM_URL (ej: https://restaurant-crm.onrender.com/)
 */
const fs = require('fs');
const path = require('path');

const crmUrl = (process.env.CRM_URL || '').replace(/\/?$/, '/'); // asegurar trailing slash
const htmlPath = path.join(__dirname, 'index.html');
let html = fs.readFileSync(htmlPath, 'utf8');
html = html.replace(/__CRM_URL__/g, crmUrl);
fs.writeFileSync(htmlPath, html);
console.log('Build OK. CRM_URL:', crmUrl || '(vacío, links relativos)');
