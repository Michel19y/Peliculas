# Sistema de Gerenciamento de Películas - Silvano

Sistema para cadastro, edição e gerenciamento de películas automotivas compatíveis com diferentes modelos de veículos. Desenvolvido para o Silvano, o sistema permite controle completo das películas cadastradas, bem como seus modelos compatíveis, marcas e quantidade em estoque. 

Utiliza conceitos de **MVC** e **Singleton**, com uma interface de painel administrativo para facilitar o uso.

## Estrutura

- `/app`: contém os arquivos de lógica da aplicação.
- `/app/models`: classes que representam entidades como Películas, Marcas e Modelos.
- `/app/controllers`: controladores intermediários entre interface e lógica.
- `/assets`: contém os arquivos de CSS, JavaScript e imagens.
- `/layouts`: layouts reutilizáveis para o site.

### Códigos Reservados do Sistema

#### Códigos de Sucesso

- **3**: Operação finalizada com sucesso (ex: exclusão, atualização ou logout).

#### Códigos de Erro

- **0**: Erro inesperado.
- **1**: Campos obrigatórios não foram informados.
- **2**: Dados inválidos.

## Funcionalidades Atuais

- Cadastro de películas com nome, marca, quantidade e modelos compatíveis.
- Edição de películas com atualização das informações e modelos.
- Exclusão de películas.
- Painel de controle para listar e buscar películas.
- Mensagens de confirmação e erro amigáveis para o usuário.
- Feedback de alterações com timeout de 5 segundos.
- Interface responsiva para uso em dispositivos móveis.

## Tecnologias Usadas

- PHP (com orientação a objetos)
- MySQL
- HTML5, CSS3 (responsivo), JavaScript
- Bootstrap 5

## Referências

- [Guia Acessibilidade W3C Developer](https://www.w3.org/WAI/tips/)
