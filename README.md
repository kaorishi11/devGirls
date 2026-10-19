# Smart Care — Gestão Hospitalar Inteligente

Sistema web de gestão hospitalar desenvolvido para o **SENAI DEV EXPERIENCE 2026 — Módulo A: Programação de Aplicações Web**. O Smart Care tem como objetivo auxiliar hospitais, clínicas e Unidades de Pronto Atendimento (UPAs) na organização do fluxo de pacientes, classificação de risco, gerenciamento de leitos e acompanhamento dos atendimentos.

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
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── pacientes.php
│   ├── leitos.php
│   └── dashboard.php
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

### Integração proposta

A integração pode ser realizada no servidor PHP por meio de uma requisição HTTP à API da OpenAI.

**Endpoint proposto:**

```http
POST https://api.openai.com/v1/chat/completions
```

**Cabeçalhos:**

```http
Authorization: Bearer SUA_CHAVE_DE_API
Content-Type: application/json
```

**Exemplo ilustrativo de requisição:**

```json
{
  "model": "gpt-4o-mini",
  "messages": [
    {
      "role": "system",
      "content": "Você é um assistente de apoio à triagem hospitalar. Analise as informações fornecidas e apresente uma sugestão estruturada de prioridade, justificativa e necessidade de avaliação profissional. Não invente dados ausentes nem substitua a avaliação clínica."
    },
    {
      "role": "user",
      "content": "Queixa: falta de ar. Saturação: 91%. Informe os sinais que exigem atenção imediata e indique que a avaliação profissional é necessária."
    }
  ],
  "temperature": 0.2
}
```

Esse exemplo demonstra o formato de uma chamada possível. Ele não representa uma requisição já executada pelo sistema nem uma classificação clínica validada.

A chave de API deverá ser armazenada em variável de ambiente ou em configuração protegida no servidor. Ela nunca deve ser disponibilizada no JavaScript do navegador ou enviada ao GitHub.

### Prompt proposto para a triagem

O prompt de instrução pode seguir esta abordagem:

> Analise os sintomas, os sinais vitais informados, o tempo de espera e as informações disponíveis do paciente. Identifique sinais de alerta, destaque informações ausentes e apresente uma sugestão de prioridade para avaliação pela equipe de saúde. Não invente dados, não forneça diagnóstico definitivo e não substitua o Protocolo de Manchester nem a avaliação do profissional habilitado. Se houver sinais de possível emergência, destaque a necessidade de avaliação imediata.

Os dados enviados ao serviço externo devem ser limitados ao estritamente necessário, preferencialmente sem nome, CPF ou outros identificadores pessoais.

### Tratamento da resposta

O servidor deverá:

1. Validar os dados recebidos do formulário.
2. Enviar somente as informações necessárias ao serviço de IA.
3. Verificar se a requisição foi concluída com sucesso.
4. Interpretar e validar a resposta antes de apresentá-la na interface.
5. Exibir a sugestão junto com um aviso de apoio à decisão clínica.
6. Permitir que o profissional habilitado confirme ou altere a classificação.

A integração deverá tratar falhas de conexão, respostas inválidas, limites de utilização e indisponibilidade do serviço.

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
- Consult