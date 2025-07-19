# Mini ERP

Sistema de controle de Pedidos, Produtos, Cupons e Estoque desenvolvido em PHP puro com MySQL e interface usando Tailwind CSS.

## 🚀 Características

- **PHP Puro** - Backend desenvolvido sem frameworks
- **MySQL** - Banco de dados relacional
- **Arquitetura MVC** - Código organizado e de fácil manutenção
- **Interface Responsiva** - Tailwind CSS para uma experiência moderna
- **Carrinho de Compras** - Gerenciamento via sessão
- **Sistema de Cupons** - Descontos com regras de negócio
- **Cálculo de Frete** - Baseado no valor do pedido
- **Consulta de CEP** - Integração com ViaCEP
- **Webhook** - API para atualização externa de pedidos
- **Envio de Email** - Confirmação automática de pedidos

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache/Nginx (XAMPP recomendado para desenvolvimento)
- Extensões PHP: PDO, mysqli, curl, openssl

## 🛠️ Instalação

### 1. Clone/Baixe o projeto
```bash
git clone <repositorio>
cd mini-erp
```

### 2. Configure o banco de dados
- Acesse o phpMyAdmin ou MySQL
- Execute o script `database/schema.sql`
- Ajuste as configurações de conexão em `config/database.php` se necessário

### 3. Configuração do ambiente
- Coloque os arquivos no diretório do servidor web (ex: `c:\xampp\htdocs\mini`)
- Certifique-se que as permissões de escrita estão configuradas para a pasta `logs/`

### 4. Acesse o sistema
- Abra o navegador e acesse: `http://localhost/mini`

## 📁 Estrutura do Projeto

```
mini/
├── app/
│   ├── controllers/          # Controllers MVC
│   │   ├── BaseController.php
│   │   ├── ProdutoController.php
│   │   ├── CarrinhoController.php
│   │   ├── CupomController.php
│   │   └── PedidoController.php
│   │
│   ├── models/              # Models MVC
│   │   ├── BaseModel.php
│   │   ├── Produto.php
│   │   ├── Variacao.php
│   │   ├── Estoque.php
│   │   ├── Cupom.php
│   │   ├── Pedido.php
│   │   ├── ItemPedido.php
│   │   ├── Endereco.php
│   │   ├── Carrinho.php
│   │   └── EmailService.php
│   │
│   └── views/               # Views MVC
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       ├── produtos/
│       ├── carrinho/
│       ├── cupons/
│       └── pedidos/
│
├── config/
│   └── database.php         # Configuração do banco
├── database/
│   └── schema.sql          # Script de criação do banco
├── logs/
│   └── webhook.log         # Log do webhook
├── index.php               # Produtos (página principal)
├── carrinho.php           # Carrinho de compras
├── cupons.php             # Gerenciamento de cupons
├── pedidos.php            # Visualização de pedidos
├── webhook.php            # API webhook
└── README.md
```

## 🗄️ Banco de Dados

### Tabelas principais:
- **produtos** - Dados dos produtos
- **variacoes** - Variações dos produtos (tamanho, cor, etc.)
- **estoques** - Controle de estoque por variação
- **cupons** - Cupons de desconto
- **pedidos** - Dados dos pedidos
- **itens_pedido** - Itens de cada pedido
- **enderecos** - Endereços de entrega

## ⚙️ Funcionalidades

### Gestão de Produtos
- ✅ Cadastro de produtos com nome e preço
- ✅ Criação de variações (P, M, G, etc.)
- ✅ Controle de estoque por variação
- ✅ Edição e exclusão de produtos
- ✅ Botão "Comprar" que adiciona ao carrinho

### Carrinho de Compras
- ✅ Adicionar/remover produtos
- ✅ Alterar quantidades
- ✅ Verificação de estoque em tempo real
- ✅ Aplicação de cupons de desconto
- ✅ Cálculo automático de frete:
  - Subtotal R$52,00 - R$166,59: frete R$15,00
  - Subtotal > R$200,00: frete grátis
  - Demais valores: frete R$20,00

### Sistema de Cupons
- ✅ Criação de cupons com código, percentual e validade
- ✅ Definição de valor mínimo do pedido
- ✅ Ativação/desativação de cupons
- ✅ Validação automática de cupons

### Gestão de Pedidos
- ✅ Listagem com filtros e estatísticas
- ✅ Visualização detalhada
- ✅ Atualização de status
- ✅ Integração com ViaCEP para consulta de endereços
- ✅ Envio de email de confirmação

### Webhook API
- ✅ Endpoint para atualização externa de pedidos
- ✅ Logs de todas as operações
- ✅ Cancelamento automático de pedidos

## 🔌 API Webhook

### Endpoint
```
POST /webhook.php
```

### Payload
```json
{
    "pedido_id": 123,
    "status": "enviado"
}
```

### Status disponíveis
- `pendente`
- `processando`
- `enviado`
- `entregue`
- `cancelado` (remove o pedido)

### Exemplo de uso
```bash
curl -X POST http://localhost/mini/webhook.php -H "Content-Type: application/json" -d "{\"pedido_id\": 1, \"status\": \"enviado\"}"
```

## 📧 Configuração de Email

Para habilitar o envio de emails, configure o servidor SMTP no arquivo `app/models/EmailService.php` ou use a função `mail()` do PHP (configurada no `php.ini`).

## 🧪 Dados de Teste

O sistema já vem com dados de exemplo:
- 2 produtos cadastrados (Camiseta e Caneca)
- Variações para cada produto
- Estoque configurado
- 3 cupons de exemplo

## 🔧 Personalização

### Cores do tema
Edite as variáveis CSS no arquivo `app/views/layouts/header.php`:
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#4f46e5',    // Cor principal
                secondary: '#10b981'   // Cor secundária
            }
        }
    }
}
```

### Configuração do banco
Ajuste as configurações em `config/database.php`:
```php
private $host = 'localhost';
private $dbname = 'mini_erp';
private $username = 'root';
private $password = '';
```

## 🚨 Troubleshooting

### Erro de conexão com banco
- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/database.php`
- Execute o script `database/schema.sql`

### Problemas com envio de email
- Configure o servidor SMTP local ou use um serviço externo
- Verifique as configurações do `php.ini`

### Erro 500
- Verifique os logs do PHP (`error_log`)
- Confirme as permissões de pasta
- Ative a exibição de erros durante desenvolvimento

## 📝 Melhorias Futuras

- [ ] Painel administrativo
- [ ] Relatórios e dashboards
- [ ] Upload de imagens para produtos
- [ ] Sistema de usuários
- [ ] Integração com gateway de pagamento
- [ ] API REST completa
- [ ] Testes automatizados

## 👨‍💻 Desenvolvimento

Sistema desenvolvido seguindo boas práticas:
- ✅ Arquitetura MVC
- ✅ Código limpo e comentado
- ✅ Segurança contra SQL Injection
- ✅ Validação de dados
- ✅ Tratamento de erros
- ✅ Logs de operações
- ✅ Interface responsiva

## 📄 Licença

Este projeto é um exemplo de desenvolvimento e pode ser usado livremente para fins educacionais e comerciais.

---

**Desenvolvido com PHP Puro + MySQL + Tailwind CSS** 🚀
