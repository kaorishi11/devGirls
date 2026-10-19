# Smart Care — Gestão Hospitalar Inteligente

O Smart Care tem como objetivo auxiliar hospitais, clínicas e Unidades de Pronto Atendimento (UPAs) na organização do fluxo de pacientes, classificação de risco, gerenciamento de leitos e acompanhamento dos atendimentos.

## 1. Sobre o projeto

O Smart Care foi idealizado para reduzir problemas como filas prolongadas, falta de visibilidade sobre leitos disponíveis, sobrecarga das equipes médicas e dificuldades na identificação de pacientes que necessitam de atendimento prioritário.

A plataforma propõe reunir informações dos pacientes, profissionais de saúde, consultórios e leitos em um único sistema, utilizando regras de prioridade e recursos de Inteligência Artificial para apoiar as decisões da equipe hospitalar.

### Objetivos

- Organizar o cadastro e o acompanhamento dos pacientes.
- Auxiliar na classificação de risco com base no Protocolo de Manchester.
- Priorizar pacientes conforme a gravidade e o tempo de espera.
- Identificar leitos e consultórios disponíveis e compatíveis com a necessidade de atendimento.
- Facilitar o monitoramento da ocupação hospitalar.
- Disponibilizar informações para apoiar a tomada de decisões dos profissionais de saúde.

## 2. Tecnologias utilizadas

| Tecnologia | Finalidade |
|---|---|
| PHP | Processamento no servidor e regras de negócio. |
| MySQL | Armazenamento dos dados do sistema. |
| HTML5 | Estrutura das páginas. |
| CSS3 | Estilização e responsividade da interface. |
| JavaScript | Interações e atualizações da interface. |
| PDO | Comunicação entre PHP e banco de dados. |
| Git e GitHub | Versionamento e compartilhamento do código. |

**Inteligência Artificial:** a integração com um provedor externo deve ser configurada e confirmada no código do projeto antes de ser considerada funcional.

## 3. Estrutura do projeto

O projeto está organizado em diretórios para separar a interface, as configurações e os componentes reutilizáveis.

```text
moduloA_devGirls/
├── assets/
│   ├── css/
│   │    ├── perfil-admin.css
│   │    ├── perfil-enfermeiro.css
│   │    ├── perfil-medico.css
│   │    ├── perfil-paciente.css
│   │    └── style.css
│   ├── js/
│   │    └── script.js
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   ├── auth.php
│   └── footer.php
├── pages/
│   ├── alertas.php
│   ├── atendimentos.php
│   ├── dashboard.php
│   ├── espacos.php
│   ├── fichas.php
│   ├── login.php
│   ├── logout.php
│   ├── pacientes.php
│   ├── profissionais.php
│   ├── triagem.php
│   └── usuarios.php
├── index.php
└── README.md
```

A estrutura acima é ilustrativa. Os nomes dos arquivos e diretórios devem corresponder aos existentes no repositório.

## 4. Instalação e execução

### Pré-requisitos

- PHP 8.0 ou superior, preferencialmente uma versão compatível com o ambiente utilizado.
- MySQL ou MariaDB.
- Apache, disponibilizado pelo XAMPP ou por outro servidor compatível.
- Navegador atualizado.
- Git, opcional para clonar o repositório.

### Passo 1 — Clonar o repositório

Abra o terminal e execute:

```bash
git clone https://github.com/kaorishi11/moduloA_devGirls.git
```

Entre na pasta do projeto:

```bash
cd moduloA_devGirls
```

### Passo 2 — Configurar o servidor local

1. Instale e abra o XAMPP.
2. Inicie os serviços Apache e MySQL.
3. Coloque a pasta do projeto dentro de `C:\xampp\htdocs\`, caso ainda não esteja nesse local.

### Passo 3 — Criar o banco de dados

Acesse `http://localhost/phpmyadmin`.

Crie um banco de dados com o nome:

```sql
CREATE DATABASE smart_care
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Importe o arquivo SQL do projeto, caso exista. Se não houver um arquivo de criação do banco, será necessário criar as tabelas conforme a estrutura utilizada pelo sistema.

### Passo 4 — Configurar a conexão

No arquivo responsável pela conexão com o banco de dados, configure os parâmetros de acordo com o ambiente local.

Exemplo de conexão utilizando PDO:

```php
<?php

$host = 'localhost';
$dbname = 'smart_care';
$username = 'root';
$password = '';

$pdo = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
```

Esse exemplo deve ser adaptado ao arquivo de configuração já existente no projeto. Em ambientes de produção, utilize credenciais seguras e não mantenha senhas no repositório.

### Passo 5 — Executar o sistema

Com o Apache e o MySQL ativos, abra:

`http://localhost/moduloA_devGirls/`

Se a aplicação utilizar outra rota inicial, acesse a página de entrada correspondente.

## 5. Inteligência Artificial

### Modelo de IA

A proposta é utilizar um modelo de linguagem acessado por API para interpretar as informações fornecidas durante a triagem e gerar sugestões estruturadas para a equipe hospitalar.

Como opção de implementação, pode ser utilizado o **GPT-4o mini, da OpenAI**. Essa escolha é uma proposta técnica e não confirma que o modelo já esteja integrado ao código atual.

O modelo pode auxiliar na interpretação de sintomas, organização das informações clínicas e geração de justificativas para as sugestões de prioridade. A classificação final deverá respeitar as regras clínicas adotadas pela instituição e ser validada por um profissional habilitado.

## 6. Lógica de triagem

A triagem proposta utiliza as cinco categorias de prioridade previstas no desafio, inspiradas no Protocolo de Manchester.

| Cor | Classificação | Tempo-alvo do desafio |
|---|---|---:|
| Vermelho | Emergência | 0 minutos |
| Laranja | Muito urgente | 10 minutos |
| Amarelo | Urgente | 60 minutos |
| Verde | Pouco urgente | 120 minutos |
| Azul | Não urgente | 240 minutos |

Esses tempos são referências definidas no enunciado do projeto, não garantias de atendimento nem substitutos de protocolos clínicos oficiais.

### Etapas da triagem

1. O paciente é cadastrado e sua ficha é preenchida com a queixa principal, os sintomas e os sinais vitais disponíveis.
2. As informações são analisadas pelo mecanismo de triagem.
3. As regras de prioridade e, quando disponível, a IA produzem uma sugestão de classificação.
4. A equipe de enfermagem avalia as informações e confirma ou modifica a classificação.
5. O paciente é encaminhado para a fila de atendimento correspondente.
6. O sistema acompanha o tempo de espera e permite identificar pacientes que necessitam de reavaliação.

### Organização da fila

A fila pode ser organizada por prioridade clínica, utilizando uma fila de prioridade ou uma ordenação equivalente.

A prioridade clínica deve prevalecer sobre a ordem de chegada. Entre pacientes com a mesma prioridade, o tempo de espera pode ser utilizado como critério de desempate, respeitando eventuais regras de reavaliação e os protocolos da instituição.

O tempo decorrido não deve, isoladamente, determinar uma mudança automática de classificação clínica.

## 7. Lógica de alocação de leitos e profissionais

A alocação deve considerar a compatibilidade entre a necessidade do paciente e os recursos disponíveis.

### Critérios considerados

- Prioridade clínica confirmada.
- Tipo de atendimento necessário.
- Tipo e situação do leito ou consultório.
- Equipamentos e recursos necessários.
- Disponibilidade do profissional responsável.
- Especialidade e setor de atendimento.
- Restrições de isolamento e outras condições aplicáveis.

### Fluxo de alocação

1. O sistema recebe a classificação confirmada do paciente.
2. Consulta os leitos e consultórios cadastrados.
3. Exclui os recursos ocupados, em higienização, em manutenção ou incompatíveis com a necessidade do atendimento.
4. Verifica a disponibilidade do profissional e a compatibilidade com o setor.
5. Sugere um recurso adequado entre os disponíveis.
6. Registra a alocação após a confirmação exigida pelo fluxo do sistema.
7. Atualiza os estados do paciente e do recurso utilizado.

Se não houver recurso compatível disponível, o paciente deverá permanecer na fila adequada, com sinalização para a equipe responsável. Casos críticos exigem encaminhamento imediato conforme o protocolo institucional, sem depender da disponibilidade de um leito específico na aplicação.

A IA pode ajudar a resumir as necessidades do paciente, mas a seleção dos recursos deve ser controlada por regras verificáveis e pela equipe de saúde.

## 8. Funcionamento sem a IA

A indisponibilidade do serviço externo não deve impedir o cadastro de pacientes nem a continuidade do atendimento.

O modo de contingência deve permitir:

- Cadastro e consulta dos pacientes.
- Classificação manual realizada pela equipe habilitada.
- Organização da fila por prioridade.
- Consulta de leitos e consultórios disponíveis.
- Registro manual da alocação.
- Sinalização de que a análise automatizada está indisponível.

O sistema deve identificar claramente quando uma sugestão foi gerada por IA e quando a operação está utilizando apenas as regras locais.

## 9. Segurança e proteção de dados

Como a plataforma pode lidar com dados pessoais e informações de saúde, sua implementação deverá considerar:

- Controle de acesso conforme o perfil do usuário.
- Autenticação segura e armazenamento protegido de senhas.
- Consultas SQL parametrizadas por PDO.
- Validação dos dados recebidos e proteção contra XSS e CSRF.
- Proteção de credenciais e chaves de API.
- Registro de ações relevantes para auditoria.
- Restrição de acesso aos prontuários.
- Tratamento dos dados conforme a Lei Geral de Proteção de Dados (LGPD).
- Uso de dados fictícios ou anonimizados durante testes e demonstrações.

As sugestões geradas por IA são recursos de apoio à decisão e não substituem a avaliação de médicos e enfermeiros habilitados.

## 10. Testes recomendados

Para avaliar o funcionamento do Smart Care, recomenda-se testar:

- Cadastro e autenticação de usuários.
- Cadastro e atualização de pacientes.
- Classificação nas cinco categorias de prioridade.
- Ordenação da fila por prioridade e tempo de espera.
- Alocação apenas de recursos compatíveis e disponíveis.
- Bloqueio da alocação de leitos ocupados ou em manutenção.
- Indisponibilidade da API de IA e ativação do modo de contingência.
- Tratamento de erros e respostas inválidas da API.
- Controle de acesso e proteção das informações.
- Atualização dos estados de pacientes e leitos.

Os testes devem utilizar dados fictícios e não podem ser utilizados para validar condutas clínicas reais.

## 11. Limitações e melhorias futuras

O projeto pode evoluir com a integração efetiva de um modelo de IA, atualização em tempo real do mapa de ocupação, previsão de demanda para os próximos 7 e 30 dias, geração de indicadores gerenciais, alertas automáticos e assistente virtual para consulta de informações autorizadas.

A implementação dessas funcionalidades deverá ser verificada no código antes de serem apresentadas como recursos concluídos.

## 12. Equipe e repositório

**Projeto:** Smart Care  
**Instituição:** SENAI — DEV EXPERIENCE 2026  
**Módulo:** A — Programação de Aplicações Web  
**Repositório:** https://github.com/kaorishi11/moduloA_devGirls

---

*Observação: este README descreve a arquitetura e a abordagem proposta para a solução. Os modelos, endpoints, tabelas, funcionalidades e instruções deverão ser ajustados para refletir fielmente a implementação existente no repositório.*