# GUIA COMPLETO - Webhook Postman

## 🚨 PROBLEMA IDENTIFICADO
O erro `"received": ""` significa que o Postman **NÃO** está enviando o JSON no body da requisição.

## ✅ CONFIGURAÇÃO CORRETA NO POSTMAN

### 1️⃣ **Method**
```
POST
```

### 2️⃣ **URL**
```
http://localhost/mini/webhook.php
```

### 3️⃣ **Headers (Importante!)**
Vá na aba **Headers** e adicione:
```
Key: Content-Type
Value: application/json
```

### 4️⃣ **Body (CRÍTICO!)**
1. Vá na aba **Body**
2. Selecione **raw** (não form-data, não x-www-form-urlencoded)
3. No dropdown ao lado, selecione **JSON**
4. Cole este JSON:

```json
{
    "pedido_id": 1,
    "status": "processando"
}
```

## 🔍 CHECKLIST DE VERIFICAÇÃO

✅ Method = POST  
✅ URL = http://localhost/mini/webhook.php  
✅ Headers: Content-Type = application/json  
✅ Body = raw + JSON selecionado  
✅ JSON válido colado no body  

## 📱 CAPTURAS DE TELA - PASSO A PASSO

### Headers Tab:
```
Content-Type | application/json
```

### Body Tab:
- [ ] form-data
- [ ] x-www-form-urlencoded  
- [x] raw  → Dropdown: JSON

```json
{
    "pedido_id": 1,
    "status": "processando"
}
```

## 🧪 TESTE RÁPIDO

### Para testar se está funcionando:
1. Configure tudo como acima
2. Clique **Send**
3. Deve retornar **200 OK** com:

```json
{
    "success": true,
    "message": "Status do pedido atualizado",
    "pedido_id": 1,
    "new_status": "processando"
}
```

## 🆘 SE AINDA DER ERRO

### Erro "JSON inválido":
- Verifique se selecionou **raw** + **JSON** no Body
- Confirme se o JSON está válido (sem vírgulas extras)

### Erro "Campo pedido_id obrigatório":
- Verifique se o JSON tem exatamente `pedido_id` (não `id`)

### Erro "Pedido não encontrado":
- Use um pedido_id que existe no banco (teste com 1, 2, 3...)

## 🚀 ALTERNATIVA RÁPIDA
Se continuar com problemas, use a página de teste:
**http://localhost/mini/teste_webhook.php**
