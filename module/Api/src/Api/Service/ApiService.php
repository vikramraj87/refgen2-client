<?php
namespace Api\Service;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\EventManager\EventManager;


class ApiService
{
    /** @var Client */
    protected $client = null;

    /** @var string */
    protected $host = '';

    /** @var Container */
    protected $container = null;

    /** @var array */
    protected $paths = array(
        'pubmed_search'     => 'pubmed/term/:term/:page',
        'pubmed_single'     => 'pubmed/id/:id',
        'collection'        => 'collection/user/:user_id/:id',
        'collection_user'   => 'collection/user/:user_id',
        'login'             => 'user/login'
    );

    /** @var EventManager */
    protected $events = null;

    /**
     * @return EventManager
     */
    public function events()
    {
        if($this->events == null) {
            $this->events = new EventManager(__CLASS__);
        }
        return $this->events;
    }

    public function getArticle($id)
    {
        $result = $this->searchCache($id);
        if(is_array($result) && isset($result['count']) &&
            is_array($result['results']) && count($result['results']) == 1)
        {
            return $result;
        }


        $vars = array(
            ':id' => $id
        );
        $path = strtr($this->paths['pubmed_single'], $vars);
        //$result = $this->doRequest($path);
        // Todo: Remove this in deployment
        $json = '{"count":1,"results":[{"id":"24456365","volume":"17","issue":"1","year":"2014","month":"Jan","day":"","pages":"142-9","issn":"1557-7600","journal":"Journal of medicinal food","journal_abbr":"J Med Food","title":"Cheonggukjang Ethanol Extracts Inhibit a Murine Allergic Asthma via Suppression of Mast Cell-Dependent Anaphylactic Reactions.","abstract":["Abstract Cheonggukjang (CGJ), a traditional Korean fermented soybean food, exerts immunomodulatory effects. Asthma is the most common chronic allergic disease to be associated with immune response to environmental allergens. In the pathogenesis of asthma, histamine is one of the important inflammatory mediators released from granules of mast cells. In this study, we evaluated the therapeutic effect of CGJ on a mouse model of ovalbumin (OVA)-induced asthma via the suppression of histamine release. C57BL\/6 mice were sensitized by intraperitoneal injection of OVA or a phosphate-buffered saline (PBS) control and then challenged with OVA inhalation. Mice were treated intraperitoneally with either 70% ethanol-extracted CGJ (CGJE) (100\u2009mg\/kg\/day) or equivalent PBS. Asthma-related inflammation was assessed by bronchoalveolar lavage fluid cell counts and histopathological and immunohistochemical analysis of lung tissues. To elucidate the mechanisms of asthma inhibition by CGJE treatment, we also examined degranulation and histamine release of compound 48\/80-induced rat peritoneal mast cells (RPMCs). Treatment with CGJE downregulated the number of eosinophils and monocytes in the lungs of mice challenged with OVA and suppressed histopathological changes, such as eosinophil infiltration, mucus accumulation, goblet cell hyperplasia, and collagen fiber deposits. Moreover, CGJE alleviated compound 48\/80-induced mast cell degranulation and histamine release from RPMCs through inhibition of calcium (Ca(2+)) uptake as well as ear swelling by infiltration of inflammatory cells. These findings demonstrated that CGJE can be used as an antiasthmatic dietary supplements candidate for histamine-mediated asthma."],"affiliation":"","authors":["Bae MJ","Shin HS","See HJ","Chai OH","Shon DH"],"article_id":"10.1089\/jmf.2013.2997,24456365","keywords":[],"pub_status":"ppublish"}]}';
        $result = Json::decode($json, Json::TYPE_ARRAY);
        if(!isset($result['count'])) {
            throw new \RuntimeException('Invalid response from the api');
        }
        return $result;
    }

    public function search($term, $page = 1)
    {
        $page = abs((int) $page);

        $result = $this->getFromCache($term, $page);

        if(is_array($result) && isset($result['count']) &&
            is_array($result['results']) && !empty($result['results']))
        {
            return $result;
        }

        $vars = array(
            ':term' => urlencode($term),
            ':page' => $page
        );
        $path = strtr($this->paths['pubmed_search'], $vars);
        // Todo: Remove this in production
        $json = '{"count":33424,"results":[{"id":"24456365","volume":"17","issue":"1","year":"2014","month":"Jan","day":"","pages":"142-9","issn":"1557-7600","journal":"Journal of medicinal food","journal_abbr":"J Med Food","title":"Cheonggukjang Ethanol Extracts Inhibit a Murine Allergic Asthma via Suppression of Mast Cell-Dependent Anaphylactic Reactions.","abstract":["Abstract Cheonggukjang (CGJ), a traditional Korean fermented soybean food, exerts immunomodulatory effects. Asthma is the most common chronic allergic disease to be associated with immune response to environmental allergens. In the pathogenesis of asthma, histamine is one of the important inflammatory mediators released from granules of mast cells. In this study, we evaluated the therapeutic effect of CGJ on a mouse model of ovalbumin (OVA)-induced asthma via the suppression of histamine release. C57BL\/6 mice were sensitized by intraperitoneal injection of OVA or a phosphate-buffered saline (PBS) control and then challenged with OVA inhalation. Mice were treated intraperitoneally with either 70% ethanol-extracted CGJ (CGJE) (100\u2009mg\/kg\/day) or equivalent PBS. Asthma-related inflammation was assessed by bronchoalveolar lavage fluid cell counts and histopathological and immunohistochemical analysis of lung tissues. To elucidate the mechanisms of asthma inhibition by CGJE treatment, we also examined degranulation and histamine release of compound 48\/80-induced rat peritoneal mast cells (RPMCs). Treatment with CGJE downregulated the number of eosinophils and monocytes in the lungs of mice challenged with OVA and suppressed histopathological changes, such as eosinophil infiltration, mucus accumulation, goblet cell hyperplasia, and collagen fiber deposits. Moreover, CGJE alleviated compound 48\/80-induced mast cell degranulation and histamine release from RPMCs through inhibition of calcium (Ca(2+)) uptake as well as ear swelling by infiltration of inflammatory cells. These findings demonstrated that CGJE can be used as an antiasthmatic dietary supplements candidate for histamine-mediated asthma."],"affiliation":"","authors":["Bae MJ","Shin HS","See HJ","Chai OH","Shon DH"],"article_id":"10.1089\/jmf.2013.2997,24456365","keywords":[],"pub_status":"ppublish"},{"id":"24454525","volume":"7","issue":"","year":"2013","month":"Aug","day":"","pages":"65","issn":"1747-0862","journal":"Journal of molecular and genetic medicine : an international journal of biomedical research","journal_abbr":"J Mol Genet Med","title":"Learning from the Cardiologists and Developing Eluting Stents Targeting the Mtor Pathway for Pulmonary Application; A Future Concept for Tracheal Stenosis.","abstract":["Tracheal stenosis due to either benign or malignant disease is a situation that the pulmonary physicians and thoracic surgeons have to cope in their everyday clinical practice. In the case where tracheal stenosis is caused due to malignancy mini-interventional interventions with laser, apc, cryoprobe, balloon dilation or with combination of more than one equipment and technique can be used. On the other hand, in the case of a benign disease such as; tracheomalacia the clinician can immediately upon diagnosis proceed to the stent placement. In both situations however; it has been observed that the stents induce formation of granuloma tissue in both or one end of the stent. Therefore a frequent evaluation of the patient is necessary, taking also into account the nature of the primary disease. Evaluation methodologies identifying different types and extent of the trachea stenosis have been previously published. However; we still do not have an effective adjuvant therapy to prevent granuloma tissue formation or prolong already treated granuloma lesions. There have been proposed many mechanisms which induce the abnormal growth of the local tissue, such as; local pressure, local stress, inflammation and vascular endothelial growth factor overexpression. Immunomodulatory agents inhibiting the mTOR pathway are capable of inhibiting the inflammatory cascade locally. In the current mini-review we will try to present the current knowledge of drug eluting stents inhibiting the mTOR pathway and propose a future application of these stents as a local anti-proliferative treatment."],"affiliation":"","authors":["Zarogoulidis P","Darwiche K","Tsakiridis K","Teschler H","Yarmus L","Zarogoulidis K","Freitag L"],"article_id":"10.4172\/1747-0862.1000065,24454525","keywords":[],"pub_status":"ppublish"},{"id":"24451063","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1879-3185","journal":"Toxicology","journal_abbr":"Toxicology","title":"Toxicity assessment of air-delivered particle-bound polybrominated diphenyl ethers.","abstract":["Human exposure to polybrominated diphenyl ether (PBDE) can occur via ingestion of indoor dust, inhalation of PBDE-contaminated air and dust-bound PBDEs. However, few studies have examined the pulmonary toxicity of particle-bound PBDEs, mainly due to the lack of an appropriate particle-cell exposure system. In this study we developed an in vitro exposure system capable of generating particle-bound PBDEs mimicking dusts containing PBDE congeners (BDEs 35, 47, 99) and delivering them directly onto lung cells grown at an air-liquid interface (ALI). The silica particles and particle-coated with PBDEs ranged in diameter from 4.3 to 4.5\u03bcm and were delivered to cells with no apparent aggregation. This experimental set up demonstrated high reproducibility and sensitivity for dosing control and distribution of particles. ALI exposure of cells to PBDE-bound particles significantly decreased cell viability and induced reactive oxygen species generation in A549 and NCI-H358 cells. In male Sprague-Dawley rats exposed via intratracheal insufflation (0.6mg\/rat), particle-bound PBDE exposures induced inflammatory responses with increased recruitment of neutrophils to the lungs compared to sham-exposed rats. The present study clearly indicates the potential of our exposure system for studying the toxicity of particle-bound compounds.","Copyright \u00a9 2014. Published by Elsevier Ireland Ltd."],"affiliation":"","authors":["Kim JS","Kl\u00f6sener J","Flor S","Peters TM","Ludewig G","Thorne PS","Robertson LW","Luthe G"],"article_id":"S0300-483X(14)00006-7,10.1016\/j.tox.2014.01.005,24451063","keywords":[],"pub_status":"aheadofprint"},{"id":"24450415","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1747-6356","journal":"Expert review of respiratory medicine","journal_abbr":"Expert Rev Respir Med","title":"Advances in interventional pulmonology.","abstract":["Interventional pulmonology (IP) remains a rapidly expanding and evolving subspecialty focused on the diagnosis and treatment of complex diseases of the thorax. As the field continues to push the leading edge of medical technology, new procedures allow for novel minimally invasive approaches to old diseases including asthma, chronic obstructive pulmonary disease and metastatic or primary lung malignancy. In addition to technologic advances, IP has matured into a defined subspecialty, requiring formal training necessary to perform the advanced procedures. This need for advanced training has led to the need for standardization of training and the institution of a subspecialty board examination. In this review, we will discuss the dynamic field of IP as well as novel technologies being investigated or employed in the treatment of thoracic disease."],"affiliation":"","authors":["Akulian J","Feller-Kopman D","Lee H","Yarmus L"],"article_id":"10.1586\/17476348.2014.880053,24450415","keywords":[],"pub_status":"aheadofprint"},{"id":"24449829","volume":"111","issue":"3","year":"2014","month":"Jan","day":"","pages":"885-6","issn":"1091-6490","journal":"Proceedings of the National Academy of Sciences of the United States of America","journal_abbr":"Proc. Natl. Acad. Sci. U.S.A.","title":"TGF-\u03b2 and lung fluid balance in ARDS.","abstract":[],"affiliation":"","authors":["Frank JA","Matthay MA"],"article_id":"1322478111,10.1073\/pnas.1322478111,24449829","keywords":[],"pub_status":"ppublish"},{"id":"24448971","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1618-2650","journal":"Analytical and bioanalytical chemistry","journal_abbr":"Anal Bioanal Chem","title":"FIB-SEM imaging of carbon nanotubes in mouse lung tissue.","abstract":["Ultrastructural characterisation is important for understanding carbon nanotube (CNT) toxicity and how the CNTs interact with cells and tissues. The standard method for this involves using transmission electron microscopy (TEM). However, in particular, the sample preparation, using a microtome to cut thin sample sections for TEM, can be challenging for investigation of regions with agglomerations of large and stiff CNTs because the CNTs cut with difficulty. As a consequence, the sectioning diamond knife may be damaged and the uncut CNTs are left protruding from the embedded block surface excluding them from TEM analysis. To provide an alternative to ultramicrotomy and subsequent TEM imaging, we studied focused ion beam scanning electron microscopy (FIB-SEM) of CNTs in the lungs of mice, and we evaluated the applicability of the method compared to TEM. FIB-SEM can provide serial section volume imaging not easily obtained with TEM, but it is time-consuming to locate CNTs in the tissue. We demonstrate that protruding CNTs after ultramicrotomy can be used to locate the region of interest, and we present FIB-SEM images of CNTs in lung tissue. FIB-SEM imaging was applied to lung tissue from mice which had been intratracheally instilled with two different multiwalled CNTs; one being short and thin, and the other longer and thicker. FIB-SEM was found to be most suitable for detection of the large CNTs (\u00d8 ca. 70\u00a0nm), and to be well suited for studying CNT agglomerates in biological samples which is challenging using standard TEM techniques."],"affiliation":"","authors":["K\u00f8bler C","Saber AT","Jacobsen NR","Wallin H","Vogel U","Qvortrup K","M\u00f8lhave K"],"article_id":"10.1007\/s00216-013-7566-x,24448971","keywords":[],"pub_status":"aheadofprint"},{"id":"24447935","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1873-2968","journal":"Biochemical pharmacology","journal_abbr":"Biochem. Pharmacol.","title":"Tamoxifen enhances erlotinib-induced cytotoxicity through down-regulating AKT-mediated thymidine phosphorylase expression in human non-small-cell lung cancer cells.","abstract":["Tamoxifen is a triphenylethylene nonsteroidal estrogen receptor (ER) antagonist used worldwide as an adjuvant hormone therapeutic agent in the treatment of breast cancer. However, the molecular mechanism of tamoxifen-induced cytotoxicity in non-small cell lung cancer (NSCLC) cells has not been identified. Thymidine phosphorylase (TP) is an enzyme of the pyrimidine salvage pathway which is upregulated in cancers. In this study, tamoxifen treatment inhibited cell survival in two NSCLC cells, H520 and H1975. Treatment with tamoxifen decreased TP mRNA and protein levels through AKT inactivation. Furthermore, expression of constitutively active AKT (AKT-CA) vectors significantly rescued the decreased TP protein and mRNA levels in tamoxifen-treated NSCLC cells. In contrast, combination treatment with PI3K inhibitors (LY294002 or wortmannin) and tamoxifen further decreased the TP expression and cell viability of NSCLC cells. Knocking down TP expression by transfection with small interfering RNA of TP enhanced the cytotoxicity and cell growth inhibition of tamoxifen. Erlotinib (Tarceva, OSI-774), an orally available small molecular inhibitor of epidermal growth factor receptor (EGFR) tyrosine kinase, is approved for clinical treatment of NSCLC. Compared to a single agent alone, tamoxifen combined with erlotinib resulted in cytotoxicity and cell growth inhibition synergistically in NSCLC cells, accompanied with reduced activation of phospho-AKT and phospho-ERK1\/2, and reduced TP protein levels. These findings may have implications for the rational design of future drug regimens incorporating tamoxifen and erlotinib for the treatment of NSCLC.","Copyright \u00a9 2014. Published by Elsevier Inc."],"affiliation":"","authors":["Ko JC","Chiu HC","Syu JJ","Jian YJ","Chen CY","Jian YT","Huang YJ","Wo TY","Lin YW"],"article_id":"S0006-2952(14)00034-3,10.1016\/j.bcp.2014.01.010,24447935","keywords":[],"pub_status":"aheadofprint"},{"id":"24447738","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1874-1754","journal":"International journal of cardiology","journal_abbr":"Int. J. Cardiol.","title":"QTc interval prolongation with high dose energy drink consumption in a healthy volunteer.","abstract":[],"affiliation":"","authors":["Shah SA","Lacey CS","Bergendahl T","Kolasa M","Riddock IC"],"article_id":"S0167-5273(14)00078-3,10.1016\/j.ijcard.2013.12.218,24447738","keywords":[],"pub_status":"aheadofprint"},{"id":"24447522","volume":"","issue":"","year":"2013","month":"Dec","day":"","pages":"","issn":"1873-5134","journal":"Patient education and counseling","journal_abbr":"Patient Educ Couns","title":"Peer educator vs. respiratory therapist support: Which form of support better maintains health and functional outcomes following pulmonary rehabilitation?","abstract":{"OBJECTIVE":"This study examined if ongoing support delivered by telephone following pulmonary rehabilitation (PR) assisted chronic obstructive pulmonary disease (COPD) patients to maintain health outcomes.","METHODS":"Phase one (n=79) compared post-rehabilitation telephone-based support delivered by peers compared to usual care (UC). The second phase (n=168) compared post-rehabilitation support from peer educators, respiratory therapists (RT), or UC. Primary outcome variables were St. George\u0027s Respiratory Questionnaire (SGRQ) total score and the six minute walk test (6MWT). Measures were obtained at baseline, immediately following PR, and six-months post PR.","RESULTS":"Six-month follow-up data for phase one was collected for 66 COPD patients (n=35 peer support, n=31 UC) and 142 for phase two (n=42 peer support, n=52 RT support, n=48 UC). Per-protocol and intention to treat (ITT) analysis in both phases found no significant group by time differences for SGRQ or 6MWT.","CONCLUSION":"Providing peer or RT support via telephone following PR was not more effective than UC for maintaining health outcomes.","PRACTICE IMPLICATIONS":"There are concerns with using peers to provide ongoing support to COPD patients. Additionally, COPD patients require a higher level of care than telephone support can provide.","0":"Copyright \u00a9 2013 Elsevier Ireland Ltd. All rights reserved."},"affiliation":"","authors":["Wong EY","Jennings CA","Rodgers WM","Selzler AM","Simmonds LG","Hamir R","Stickland MK"],"article_id":"S0738-3991(13)00522-3,10.1016\/j.pec.2013.12.008,24447522","keywords":[],"pub_status":"aheadofprint"},{"id":"24446897","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1600-0684","journal":"Journal of medical primatology","journal_abbr":"J. Med. Primatol.","title":"Plasma antibody profiles in non-human primate tuberculosis.","abstract":{"BACKGROUND":"Tuberculosis (TB) in non-human primates (NHPs) is highly contagious, requiring efficient identification of animals infected with Mycobacterium tuberculosis. Tuberculin skin test is usually used but lacks desirable sensitivity\/specificity and efficiency.","METHODS":"We aimed to develop an immunoassay for plasma antibodies against M.\u00a0tuberculosis. A key challenge is that not all infected animals contain antibodies against the same M.\u00a0tuberculosis antigen. Therefore, a multiplex panel of 28 antigens (Luminex(\u00ae) -Platform) was developed.","RESULTS":"Data revealed antibodies against eight antigens (Rv3875, Rv3875-Rv3874 fusion, Rv3874, Rv0934, Rv3881, Rv1886c, Rv2031, Rv3841) in experimentally infected (M.\u00a0tuberculosis strains: Erdman and H37Rv) NHPs (rhesus and cynomolgus macaques). In a naturally acquired M.\u00a0tuberculosis infection, rhesus macaques (n\u00a0=\u00a015) with lung TB pathology (n\u00a0=\u00a010) contained antibodies to five additional antigens (Rv0831, Rv2220, Rv0054, Rv1099, and Rv0129c).","CONCLUSIONS":"Results suggest that this user-friendly and easily implementable multiplex panel, containing 13\u00a0M.\u00a0tuberculosis antigens, may provide a high-throughput alternative for NHP TB screening.","0":"\u00a9 2014 The Authors. Journal of Medical Primatology published by John Wiley \u0026 Sons Ltd."},"affiliation":"","authors":["Ravindran R","Krishnan VV","Dhawan R","Wunderlich ML","Lerche NW","Flynn JL","Luciw PA","Khan IH"],"article_id":"10.1111\/jmp.12097,24446897","keywords":[],"pub_status":"aheadofprint"}]}';

        $result = Json::decode($json, Json::TYPE_ARRAY);
        //$result = $this->doRequest($path);
        if(!isset($result['count'])) {
            throw new \RuntimeException('Invalid response from the api');
        }
        if($result['count']) {
            $this->cacheResults($result, $term, $page);
        }
        return $result;
    }

    public function getCollection($id, $userId)
    {
        $vars = array(
            ':id' => (int) $id,
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path);
    }

    public function getCollections($userId)
    {
        $vars = array(
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection_user'], $vars);
        return $this->doRequest($path);
    }

    public function createCollection($userId, $data)
    {
        $vars = array(
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection_user'], $vars);
        return $this->doRequest($path, $data, Request::METHOD_POST);
    }

    public function updateCollection($id, $userId, $data)
    {
        $vars = array(
            ':user_id' => (int) $userId,
            ':id'      => (int) $id
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path, $data, Request::METHOD_PUT);
    }

    public function deleteCollection($id, $userId)
    {
        $vars = array(
            ':user_id' => (int) $userId,
            ':id'      => (int) $id
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path, array(), Request::METHOD_DELETE);
    }

    public function authenticate($email, $password)
    {
        $data = array(
            'email' => $email,
            'password' => $password
        );
        $path = $this->paths['login'];
        return $this->doRequest($path, $data, Request::METHOD_POST);
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = rtrim($host, '/');
        return $this;
    }

    /**
     * @param Container $container
     * @return $this
     */
    public function setCacheContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    protected function doRequest($path, $data = array(), $method = Request::METHOD_GET)
    {
        if($this->client === null) {
            throw new \RuntimeException('Client not set in Api Service');
        }
        if($this->host === '') {
            throw new \RuntimeException('Host not set in Api Service');
        }

        $url = $this->host . '/' . ltrim($path, '/');

        $client = $this->client;
        $client->setUri($url);
        $client->setMethod($method);

        if(!empty($data)) {
            $client->setParameterPost($data);
        }
        $this->events()->trigger(
            'dispatch.pre',
            $this,
            array(
                'request' => $client->getRequest()->toString()
            )
        );
        /** @var Response $response */
        $response = $client->send();

        if($response->isSuccess()) {
            $this->events()->trigger(
                'dispatch.post',
                $this,
                array(
                    'error' => false
                )
            );
            $resArray = Json::decode($response->getBody(), Json::TYPE_ARRAY);
            if(!is_array($resArray)) {
                throw new \RuntimeException('Invalid data returned from the server');
            }
            return $resArray;
        }

        $this->events()->trigger(
            'dispatch.post',
            $this,
            array(
                'error' => true,
                'msg'   => $response->getBody()
            )
        );
        throw new \RuntimeException('Server communication error');
    }

    protected function cacheResults(array $results = array(), $term, $page)
    {
        $articles = $results['results'];
        if(empty($articles) || !$this->container instanceof Container) {
            return false;
        }
        $tmp = array();
        foreach($articles as $article) {
            $id = $article['id'];
            $tmp[$id] = $article;
        }
        $session = $this->container;
        $session->cache = $tmp;
        $session->term  = $term;
        $session->page  = $page;
        $session->count = $results['count'];
        return true;
    }

    protected function getFromCache($term, $page)
    {
        if(!$this->container instanceof Container) {
            return null;
        }
        $session = $this->container;
        if(isset($session->term)) {
            if(isset($session->page)) {
                if(isset($session->count)) {
                    if($session->term == $term && $session->page == $page) {
                        return array(
                            'count' => $session->count,
                            'results' => $session->cache
                        );
                    }
                }
            }
        }
        return null;
    }

    protected function searchCache($id)
    {
        if(!$this->container instanceof Container) {
            return null;
        }
        $article = null;
        $cache = $this->container->cache;
        if(isset($cache[$id])) {
            $article = array(
                'count' => 1,
                'results' => array($cache[$id])
            );
        }
        return $article;
    }
}