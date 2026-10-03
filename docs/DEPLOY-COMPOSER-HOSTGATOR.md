# Deploy de novos pacotes Composer (Laravel na HostGator)

Guia rápido para quando você adicionar ou atualizar um pacote no `composer.json`.

> O Git Version Control do cPanel só copia os arquivos. Ele **não** roda o Composer. Por isso, depois do deploy, é preciso instalar as dependências manualmente no servidor.

---

## 1. No computador (local)

1. Instale o pacote:
   ```bash
   composer require vendor/pacote
   ```
   Isso atualiza o `composer.json` e o `composer.lock`.
2. Se o pacote tem configuração, publique:
   ```bash
   php artisan vendor:publish --provider="Vendor\Pacote\ServiceProvider"
   ```
3. Confira se o nome dos arquivos bate com o nome das classes, com a mesma maiúscula e minúscula (`IsAdmin.php` para `class IsAdmin`). O Windows perdoa o erro, o Linux do servidor não.
4. Se precisar renomear só a caixa de um arquivo no Git, faça em duas etapas:
   ```bash
   git mv app/Http/Middleware/isAdmin.php app/Http/Middleware/tmp.php
   git mv app/Http/Middleware/tmp.php app/Http/Middleware/IsAdmin.php
   ```
5. Faça commit (incluindo o `composer.lock`) e envie:
   ```bash
   git add .
   git commit -m "Descrição da mudança"
   git push
   ```

## 2. No painel da HostGator

6. Abra **Git Version Control**, clique em **Manage** no repositório e faça:
   - *Update from Remote*
   - *Deploy HEAD Commit*

## 3. No terminal do servidor (SSH ou Terminal do cPanel)

7. Entre na pasta do projeto (a mesma onde fica o `artisan`) e rode:
   ```bash
   curl -sS https://getcomposer.org/installer | php
   php composer.phar install --no-dev --optimize-autoloader
   ```

8. **Se pedir token do GitHub** (`Bad credentials` ou limite de requisições):
   - Abra o link que o Composer mostrou e crie um token **fine-grained**.
   - Nome curto (máximo de 40 caracteres), por exemplo `composer-hostgator`.
   - Expiração curta (7 ou 30 dias).
   - Acesso: **Public repositories (read-only)**, sem permissões extras.
   - Cole no prompt `Token (hidden):` (o texto não aparece, é normal).
   - Nunca cole o token em chats, no código ou em arquivos do projeto.

9. Faça a limpeza:
   ```bash
   rm composer.phar
   rm ~/.composer/auth.json
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

10. Revogue o token no GitHub: *Settings → Developer settings → Personal access tokens*.

## 4. Conferências finais

11. Leia a saída do Composer procurando `does not comply with psr-4`. Se aparecer, o nome do arquivo está diferente do nome da classe. Corrija localmente e repita o deploy.
12. Confira se o pacote foi instalado:
    ```bash
    ls vendor/nome-do-vendor
    ```
13. Veja se o `package:discover` listou o pacote como `DONE`.
14. Teste no navegador as telas que usam o pacote novo.

---

## Lembretes rápidos

- O Composer remove o que não está mais no `composer.lock` (como aconteceu com o `yajra/laravel-datatables-oracle`). Confira se ainda precisa do pacote antes de dar push.
- Nunca deixe o `composer.phar` dentro da pasta do site, porque ele fica acessível pela web.
- Se só precisar regenerar o autoload (por exemplo, depois de renomear arquivos):
  ```bash
  curl -sS https://getcomposer.org/installer | php
  php composer.phar dump-autoload -o --no-dev
  rm composer.phar
  ```
- Neste servidor, `composer` não existe como comando global e o caminho `/opt/cpanel/composer/bin/composer` também não existe. Use sempre o `composer.phar`.

## Plano B (se o Composer não funcionar no servidor)

1. Localmente, com a mesma versão de PHP do servidor:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. Compacte a pasta `vendor/` em um `.zip`.
3. Envie pelo Gerenciador de Arquivos do cPanel e extraia na pasta do projeto.
4. Apague `bootstrap/cache/packages.php` e `bootstrap/cache/services.php` no servidor, para o Laravel regenerar o cache de pacotes.
5. Rode `php artisan config:clear`.

Evite commitar o `vendor/` no Git: o repositório fica pesado e pode haver conflito entre versões de PHP.

## Específico do mews/purifier

- Pasta de cache do HTMLPurifier precisa existir e ter escrita: `storage/app/purifier`.
- Para não gerar `<p>` automático nos textos limpos, em `config/purifier.php` (perfil `default`):
  ```php
  'AutoFormat.AutoParagraph' => false,
  ```
- Teste rápido no servidor:
  ```bash
  php artisan tinker --execute="echo \Mews\Purifier\Facades\Purifier::clean('<script>alert(1)</script><b>ok</b>');"
  ```
