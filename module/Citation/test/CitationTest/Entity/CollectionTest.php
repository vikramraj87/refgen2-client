<?php
namespace CitationTest\Entity;

use PHPUnit_Framework_TestCase;
use Zend\Json\Json;
use Citation\Entity\Collection;
use Article\Entity\Citation\Vancouver;
use Article\Entity\Article;
use Zend\Stdlib\Hydrator\ClassMethods;


class CollectionTest extends PHPUnit_Framework_TestCase
{
    /** @var Article[] */
    protected $articles;
    protected $collection;

    public function setUp()
    {
        $jsonString = '[{"id":"24412268","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1096-0945","journal":"Experimental and molecular pathology","journal_abbr":"Exp. Mol. Pathol.","title":"Molecular fixative enables expression microarray analysis of microdissected clinical cervical specimens.","abstract":["Formalin-fixed tissue has been a mainstay of clinical pathology laboratories, but formalin alters many biomolecules, including nucleic acids and proteins. Meanwhile, frozen tissues contain better-preserved biomolecules, but tissue morphology is affected, limiting their diagnostic utility. Molecular fixatives promise to bridge this gap by simultaneously preserving morphology and biomolecules, enabling clinical diagnosis and molecular analyses on the same specimen. While previous reports have broadly evaluated the use of molecular fixative in various human tissues, we present here the first detailed assessment of the applicability of molecular fixative to both routine histopathological diagnosis and molecular analysis of cervical tissues. Ten specimens excised via the loop electrosurgical excision procedure, which removes conical tissue samples from the cervix, were cut into alternating pieces preserved in either formalin or molecular fixative. Cervical specimens preserved in molecular fixative were easily interpretable, despite featuring more eosinophilic cytoplasm and more recognizable chromatin texture than formalin-fixed specimens. Immunohistochemical staining patterns of p16 and Ki-67 were similar between fixatives, although Ki-67 staining was stronger in the molecular fixative specimens. The RNA of molecular fixative specimens from seven cases representing various dysplasia grades was assessed for utility in expression microarray analysis. Cluster analysis and scatter plots of duplicate samples suggest that data of sufficient quality can be obtained from as little as 50ng of RNA from molecular fixative samples. Taken together, our results show that molecular fixative may be a more versatile substitute for formalin, simultaneously preserving tissue morphology for clinical diagnosis and biomolecules for immunohistochemistry and gene expression analysis.","Copyright \u00a9 2013 Elsevier Inc. All rights reserved."],"affiliation":"","authors":["Li G","van Niekerk D","Miller D","Ehlen T","Garnis C","Follen M","Guillaud M","Macaulay C"],"article_id":"24412268,S0014-4800(13)00153-6,10.1016\/j.yexmp.2013.12.007","keywords":[],"pub_status":"aheadofprint"},{"id":"24395863","volume":"","issue":"","year":"2014","month":"Jan","day":"","pages":"","issn":"1527-7755","journal":"Journal of clinical oncology : official journal of the American Society of Clinical Oncology","journal_abbr":"J. Clin. Oncol.","title":"Phase III Randomized Trial of Weekly Cisplatin and Irradiation Versus Cisplatin and Tirapazamine and Irradiation in Stages IB2, IIA, IIB, IIIB, and IVA Cervical Carcinoma Limited to the Pelvis: A Gynecologic Oncology Group Study.","abstract":{"PURPOSE":"This prospective, randomized phase III intergroup trial of the Gynecologic Oncology Group and National Cancer Institute of Canada Clinical Trials Group was designed to test the effectiveness and safety of adding the hypoxic cell sensitizer tirapazamine (TPZ) to standard cisplatin (CIS) chemoradiotherapy in locally advanced cervix cancer.","PATIENTS AND METHODS":"Patients with locally advanced cervix cancer were randomly assigned to CIS chemoradiotherapy versus CIS\/TPZ chemoradiotherapy. Primary end point was progression-free survival (PFS). Secondary end points included overall survival (OS) and tolerability.","RESULTS":"PFS was evaluable in 387 of 402 patients randomly assigned over 36 months, with enrollment ending in September 2009. Because of the lack of TPZ supply, the study did not reach its original target accrual goal. At median follow-up of 28.3 months, PFS and OS were similar in both arms. Three-year PFS for the TPZ\/CIS\/RT and CIS\/RT arms were 63.0% and 64.4%, respectively (log-rank P = .7869). Three-year OS for the TPZ\/CIS\/RT and CIS\/RT arms were 70.5% and 70.6%, respectively (log-rank P = .8333). A scheduled interim safety analysis led to a reduction in the starting dose for the TPZ\/CIS arm, with resulting tolerance in both treatment arms.","CONCLUSION":"TPZ\/CIS chemoradiotherapy was not superior to CIS chemoradiotherapy in either PFS or OS, although definitive commentary was limited by an inadequate number of events (progression or death). TPZ\/CIS chemoradiotherapy was tolerable at a modified starting dose."},"affiliation":"","authors":["Disilvestro PA","Ali S","Craighead PS","Lucci JA","Lee YC","Cohn DE","Spirtos NM","Tewari KS","Muller C","Gajewski WH","Steinhoff MM","Monk BJ"],"article_id":"JCO.2013.51.4265,10.1200\/JCO.2013.51.4265,24395863","keywords":[],"pub_status":"aheadofprint"},{"id":"24383086","volume":"441","issue":"2","year":"2013","month":"Nov","day":"","pages":"519-24","issn":"1090-2104","journal":"Biochemical and biophysical research communications","journal_abbr":"Biochem. Biophys. Res. Commun.","title":"Sarsasapogenin induces apoptosis via the reactive oxygen species-mediated mitochondrial pathway and ER stress pathway in HeLa cells.","abstract":["Sarsasapogenin is a sapogenin from the Chinese medical herb Anemarrhena asphodeloides Bunge. In the present study, we revealed that sarsasapogenin exhibited antitumor activity by inducing apoptosis in vitro as determined by Hoechst staining analysis and double staining of Annexin V-FITC\/PI. In addition, cell cycle arrest in G2\/M phase was observed in sarsasapogenin-treated HeLa cells. Moreover, the results revealed that perturbations in the mitochondrial membrane were associated with the deregulation of the Bax\/Bcl-2 ratio which led to the upregulation of cytochrome c, followed by activation of caspases. Meanwhile, treatment of sarsasapogenin also activated Unfolded Protein Response (UPR) signaling pathways and these changes were accompanied by increased expression of CHOP. Salubrinal (Sal), a selective inhibitor of endoplasmic reticulum (ER) stress, partially abrogated the sarsasapogenin-related cell death. Furthermore, sarsasapogenin provoked the generation of reactive oxygen species, while the antioxidant N-acetyl cysteine (NAC) effectively blocked the activation of ER stress and apoptosis, suggesting that sarsasapogenin-induced reactive oxygen species is an early event that triggers ER stress mitochondrial apoptotic pathways. Taken together, the results demonstrate that sarsasapogenin exerts its antitumor activity through both reactive oxygen species (ROS)-mediate mitochondrial dysfunction and ER stress cell death."],"affiliation":"","authors":["Shen S","Zhang Y","Zhang R","Gong X"],"article_id":"24383086","keywords":["Anemarrhena","chemistry","Antineoplastic Agents","pharmacology","Cell Cycle Checkpoints","Cinnamates","pharmacology","Cytochromes c","biosynthesis","Drugs, Chinese Herbal","pharmacology","Endoplasmic Reticulum Stress","drug effects","Female","G1 Phase Cell Cycle Checkpoints","drug effects","HeLa Cells","Humans","M Phase Cell Cycle Checkpoints","drug effects","Mitochondria","drug effects","Mitochondrial Membranes","drug effects","Reactive Oxygen Species","metabolism","Spirostans","pharmacology","Thiourea","analogs \u0026 derivatives","Transcription Factor CHOP","biosynthesis","Unfolded Protein Response","Uterine Cervical Neoplasms","metabolism","bcl-2-Associated X Protein","biosynthesis"],"pub_status":"ppublish"},{"id":"24374486","volume":"23","issue":"4","year":"2013","month":"Dec","day":"","pages":"144-53","issn":"2224-7041","journal":"East Asian archives of psychiatry : official journal of the Hong Kong College of Psychiatrists = Dong Ya jing shen ke xue zhi : Xianggang jing shen ke yi xue yuan qi kan","journal_abbr":"East Asian Arch Psychiatry","title":"Psychiatric morbidity in chinese women after cervical cancer treatment in a regional gynaecology clinic.","abstract":["OBJECTIVES. To identify the prevalence and factors associated with psychiatric disorders in Chinese cervical cancer survivors. METHODS. A cross-sectional study was conducted from May 2011 to April 2012 at the specialist gynaecology outpatient clinic at Pamela Youde Nethersole Eastern Hospital, Hong Kong. All cervical cancer patients who had completed treatment were consecutively recruited. They were interviewed using the Chinese-Bilingual Structured Clinical Interview for DSM-IV Axis I Disorders, Patient Research version. Socio-demographic data and clinical information were collected from the patients and their hospital records were reviewed. RESULTS. A total of 113 patients were recruited into the study. The point prevalence of psychiatric disorders as a group in cervical cancer survivors was 37%. The point prevalence of depressive disorders, anxiety disorders, and schizophrenia were 31%, 16%, and 2%, respectively. Major depressive disorder was the most common mood disorder and generalised anxiety disorder the most common anxiety disorder. Younger age, a history of psychiatric illness, fatigue, menopausal symptoms, and pain were independent predictors of current psychiatric disorders. CONCLUSION. Psychiatric disorders, predominantly depressive and anxiety disorders, are common in Chinese cervical cancer survivors. Identification of independent predictors can help gynaecologists detect these disorders earlier and arrange appropriate interventions."],"affiliation":"","authors":["Lau KL","Yim PH","Cheung EY"],"article_id":"24374486","keywords":[],"pub_status":"ppublish"},{"id":"24371711","volume":"6","issue":"","year":"2013","month":"","day":"","pages":"22-4","issn":"2211-338X","journal":"Gynecologic oncology case reports","journal_abbr":"Gynecol Oncol Case Rep","title":"Papillary serous carcinoma of the cervix mixed with squamous cells: A report of the first case.","abstract":{"OBJECTIVE":"Primary papillary serous carcinoma (PPSC) of the cervix is rarely recognized, with the aggressive and unpredictable course. Here we report a case of primary adenosquamous papillary serous carcinoma of the cervix in a woman who underwent comprehensive treatment.","CASE":"A 53-year-old woman presented with irregular vaginal bleeding in hospital. The patient with a diagnosis of PPSC by an intracolposcopic biopsy received radical hysterectomy with bilateral salpingo-oophorectomy, right pelvic lymphadenectomy, left pelvic lymph node dissection, and postoperative concurrent chemoradiotherapy. Postoperative immunohistochemistry showed that CK5\/6, CK7, P16, CEA, CA12-5 and P53 were positive. During 17\u00a0months after operation, the patient demonstrated distant metastases of lymph nodes and finally died of brain metastasis.","CONCLUSIONS":"Papillary serous adenocarcinoma of the cervix mixed with squamous cell carcinoma has not been reported since now, and here, this is the first documented case. Despite surgery and concurrent chemoradiotherapy, which were reported as effective therapeutic strategies for papillary serous adenocarcinoma of the cervix, the patient showed a poorer prognosis. Taken together, papillary serous adenosquamous carcinoma of the cervix could be more malignant than pure papillary serous adenocarcinoma."},"affiliation":"","authors":["Tang W","Zhang Z","Yao H","Zeng Z","Wan G"],"article_id":"10.1016\/j.gynor.2013.07.003,S2211-338X(13)00059-8,24371711,PMC3862309","keywords":[],"pub_status":"epublish"},{"id":"24367842","volume":"77","issue":"10","year":"2013","month":"Nov-Dec","day":"","pages":"595-8","issn":"0010-6178","journal":"Connecticut medicine","journal_abbr":"Conn Med","title":"Advanced pelvic organ prolapse and routine health screening.","abstract":{"OBJECTIVE":"To evaluate whether patients with advanced pelvic-organ prolapse (POP) were less likely than controls to obtain screening Papanicolaou (Pap) test, mammography, and colonoscopy.","STUDY DESIGN":"Records were reviewed from 7\/2\/2010 through 4\/22\/2011. We identified patients with advanced POP, defined as prolapse \u003E or = 4 cm beyond the hymenal ring, and made age- and parity-matched controls from patients whose prolapse was \u003C4 cm. Compliance for screening of cervical, breast and colon cancers was compared between the two groups.","RESULTS":"Of 933 records, we identified 51 patients with advanced POP and 51 controls. Neither Pap test nor colonoscopy screening differed between the groups (McNemar chi2, P=1.00; McNemar chi2, P=1.00). Mammogram screening did not differ statistically; however, there was a trend towards neglecting screening in the advanced POP group (McNemar chi2, P=0.057).","CONCLUSION":"Patients with POP \u003E or = 4 cm beyond the hymenal ring were equally as likely to obtain routine health screening as age- and parity-matched controls whose POP measured \u003C4 cm."},"affiliation":"","authors":["Suozzi BA","Galffy A","O\u0027Sullivan DM","Tulikangas PK"],"article_id":"24367842","keywords":["Aged","Aged, 80 and over","Breast Neoplasms","diagnosis","Case-Control Studies","Colonic Neoplasms","diagnosis","Colonoscopy","statistics \u0026 numerical data","Female","Humans","Mammography","statistics \u0026 numerical data","Mass Screening","methods","Middle Aged","Papanicolaou Test","statistics \u0026 numerical data","Pelvic Organ Prolapse","diagnosis","Uterine Cervical Neoplasms","diagnosis"],"pub_status":"ppublish"},{"id":"24340745","volume":"","issue":"8","year":"2013","month":"Aug","day":"","pages":"24-6","issn":"0869-2084","journal":"Klinicheskaia laboratornaia diagnostika","journal_abbr":"Klin. Lab. Diagn.","title":"[The identification of viruses of human papilloma of high carcinogenic risk and evaluation of physical status of viral DNA using technique of polymerase-chain reaction under affection of cervical epithelium].","abstract":["The DNA of virus of human papilloma of high carcinogenic risk was detected in 116 cervical samples. At that, the morphological symptoms of background processes are detected in 19 samples, CIN 1 in 9, CIN 2 in 23, CIN 3 in 54 (and out of them carcinoma in situ in 13), epidermoid cancer (squamous cell carcinoma) in 11 cases. The viral load of human papilloma of high carcinogenic risk in all samples of DNA exceeded threshold of clinical value (3 lg copies of DNA of human papilloma\/105 cells). The genetic typing of human papilloma of high carcinogenic risk revealed the dominance of human papilloma of type 16 in 49.7%, type 33 in 15.3%, type 31 in 12.3% and type 45 in 5.5%. In women with background processes in cervix of the uterus DNA of human papilloma type 16 was detected more often in episome form. In case of dysplastic alterations of epithelium and cervical cancer DNA of human papilloma type 16 is detected in mixt form with different degree of integration into cell genome."],"affiliation":"","authors":["Viazovaia AA","Kuevda DA","Trofimova OB","Shipulina OIu","Ershov VA","Lialina LV","Narvskaia OV"],"article_id":"24340745","keywords":["Adult","Aged","Carcinoma, Squamous Cell","genetics","Cervix Uteri","DNA, Viral","genetics","Epithelium","metabolism","Female","Human papillomavirus 16","genetics","Humans","Middle Aged","Papillomavirus Infections","genetics","Polymerase Chain Reaction","methods","Uterine Cervical Neoplasms","genetics"],"pub_status":"ppublish"},{"id":"24335377","volume":"40","issue":"13","year":"2013","month":"Dec","day":"","pages":"2589-92","issn":"0385-0684","journal":"Gan to kagaku ryoho. Cancer \u0026 chemotherapy","journal_abbr":"Gan To Kagaku Ryoho","title":"[Primary diffuse large B-cell lymphoma of the uterine cervix successfully treated with rituximabplus cyclophosphamide, doxorubicin, vincristine, and prednisone chemotherapy-a case report].","abstract":["Primary malignant lymphoma of the uterine cervix is a rare disease, and the therapeutic strategy has not been clearly established. A 45-year old woman presented with vaginal bleeding and hypermenorrhea in January 2012. Physical examination revealed a mass in the pelvic cavity approximately the size of a neonate\u0027s head. Pelvic magnetic resonance imaging(MRI) showed a solid mass 11 cm in size in the uterine cervix with homogeneous low intensity on T1-weighted images, iso-high intensity on T2-weighted images, and heterogeneous iso-high intensity on gadolinium-diethylenetriaminepentaacetate(Gd- DTPA)-enhanced images. Multiple lymphadenopathy were also detected in the pelvis. The Papanicolaou smear indicated class 5 cervical cytology, and a subsequent histological examination by a punch biopsy of the cervix showed diffuse infiltration of medium- to large-sized mononuclear cells that stained positive for CD20 and CD79a and negative for CD3, CD5, and EBER. Bone marrow biopsy revealed no abnormality. Positron emission tomography-computed tomography(PET-CT)showed strong fluorodeoxyglucose(FDG)accumulation in the uterine cervix mass, and in the pelvic and right inguinal lymphadenopathy. The patient was diagnosed with diffuse large B-cell lymphoma of the uterine cervix, Ann Arbor stage II AE. She was successfully treated with 8 courses of rituximab plus cyclophosphamide, doxorubicin, vincristine, and prednisone(R-CHOP) chemotherapy, and maintains a complete remission."],"affiliation":"","authors":["Hashimoto A","Fujimi A","Kanisawa Y","Matsuno T","Okuda T","Minami S","Doi T","Ishikawa K","Uemura N","Jyomen Y","Tomaru U"],"article_id":"24335377","keywords":["Antibodies, Monoclonal, Murine-Derived","administration \u0026 dosage","Antineoplastic Combined Chemotherapy Protocols","administration \u0026 dosage","Cyclophosphamide","administration \u0026 dosage","Doxorubicin","administration \u0026 dosage","Female","Humans","Lymphoma, Large B-Cell, Diffuse","drug therapy","Middle Aged","Neoplasm Invasiveness","Neoplasm Staging","Prednisone","administration \u0026 dosage","Uterine Cervical Neoplasms","drug therapy","Vincristine","administration \u0026 dosage"],"pub_status":"ppublish"},{"id":"24332295","volume":"31 Suppl 7","issue":"","year":"2013","month":"Dec","day":"","pages":"H1-H31","issn":"1873-2518","journal":"Vaccine","journal_abbr":"Vaccine","title":"Comprehensive control of human papillomavirus infections and related diseases.","abstract":["Infection with human papillomavirus (HPV) is recognized as one of the major causes of infection-related cancer worldwide, as well as the causal factor in other diseases. Strong evidence for a causal etiology with HPV has been stated by the International Agency for Research on Cancer for cancers of the cervix uteri, penis, vulva, vagina, anus and oropharynx (including base of the tongue and tonsils). Of the estimated 12.7 million new cancers occurring in 2008 worldwide, 4.8% were attributable to HPV infection, with substantially higher incidence and mortality rates seen in developing versus developed countries. In recent years, we have gained tremendous knowledge about HPVs and their interactions with host cells, tissues and the immune system; have validated and implemented strategies for safe and efficacious prophylactic vaccination against HPV infections; have developed increasingly sensitive and specific molecular diagnostic tools for HPV detection for use in cervical cancer screening; and have substantially increased global awareness of HPV and its many associated diseases in women, men, and children. While these achievements exemplify the success of biomedical research in generating important public health interventions, they also generate new and daunting challenges: costs of HPV prevention and medical care, the implementation of what is technically possible, socio-political resistance to prevention opportunities, and the very wide ranges of national economic capabilities and health care systems. Gains and challenges faced in the quest for comprehensive control of HPV infection and HPV-related cancers and other disease are summarized in this review. The information presented may be viewed in terms of a reframed paradigm of prevention of cervical cancer and other HPV-related diseases that will include strategic combinations of at least four major components: 1) routine introduction of HPV vaccines to women in all countries, 2) extension and simplification of existing screening programs using HPV-based technology, 3) extension of adapted screening programs to developing populations, and 4) consideration of the broader spectrum of cancers and other diseases preventable by HPV vaccination in women, as well as in men. Despite the huge advances already achieved, there must be ongoing efforts including international advocacy to achieve widespread-optimally universal-implementation of HPV prevention strategies in both developed and developing countries. This article summarizes information from the chapters presented in a special ICO Monograph \u0027Comprehensive Control of HPV Infections and Related Diseases\u0027 Vaccine Volume 30, Supplement 5, 2012. Additional details on each subtopic and full information regarding the supporting literature references may be found in the original chapters.","Copyright \u00a9 2013 Elsevier Ltd. All rights reserved."],"affiliation":"","authors":["Bosch FX","Broker TR","Forman D","Moscicki AB","Gillison ML","Doorbar J","Stern PL","Stanley M","Arbyn M","Poljak M","Cuzick J","Castle PE","Schiller JT","Markowitz LE","Fisher WA","Canfell K","Denny LA","Franco EL","Steben M","Kane MA","Schiffman M","Meijer CJ","Sankaranarayanan R","Castellsagu\u00e9 X","Kim JJ","Brotons M","Alemany L","Albero G","Diaz M","Sanjos\u00e9 Sd"," "],"article_id":"S0264-410X(13)01346-7,10.1016\/j.vaccine.2013.10.003,24332295","keywords":[],"pub_status":"ppublish"},{"id":"24331862","volume":"","issue":"","year":"2013","month":"Dec","day":"","pages":"","issn":"1879-0887","journal":"Radiotherapy and oncology : journal of the European Society for Therapeutic Radiology and Oncology","journal_abbr":"Radiother Oncol","title":"Hybrid adaptive radiotherapy with on-line MRI in cervix cancer IMRT.","abstract":{"PURPOSE":"Substantial organ motion and tumor shrinkage occur during radiotherapy for cervix cancer. IMRT planning studies have shown that the quality of radiation delivery is influenced by these anatomical changes, therefore the adaptation of treatment plans may be warranted. Image guidance with off-line replanning, i.e. hybrid-adaptation, is recognized as one of the most practical adaptation strategies. In this study, we investigated the effects of soft tissue image guidance using on-line MR while varying the frequency of off-line replanning on the adaptation of cervix IMRT.","MATERIALS AND METHOD":"33 cervical cancer patients underwent planning and weekly pelvic MRI scans during radiotherapy. 5 patients of 33 were identified in a previous retrospective adaptive planning study, in which the coverage of gross tumor volume\/clinical target volume (GTV\/CTV) was not acceptable given single off-line IMRT replan using a 3mm PTV margin with bone matching. These 5 patients and a randomly selected 10 patients from the remaining 28 patients, a total of 15 patients of 33, were considered in this study. Two matching methods for image guidance (bone to bone and soft tissue to dose matrix) and three frequencies of off-line replanning (none, single, and weekly) were simulated and compared with respect to target coverage (cervix, GTV, lower uterus, parametrium, upper vagina, tumor related CTV and elective lymph node CTV) and OAR sparing (bladder, bowel, rectum, and sigmoid). Cost (total process time) and benefit (target coverage) were analyzed for comparison.","RESULTS":"Hybrid adaptation (image guidance with off-line replanning) significantly enhanced target coverage for both 5 difficult and 10 standard cases. Concerning image guidance, bone matching was short of delivering enough doses for 5 difficult cases even with a weekly off-line replan. Soft tissue image guidance proved successful for all cases except one when single or more frequent replans were utilized in the difficult cases. Cost and benefit analysis preferred (soft tissue) image guidance over (frequent) off-line replanning.","CONCLUSIONS":"On-line MRI based image guidance (with combination of dose distribution) is a crucial element for a successful hybrid adaptive radiotherapy. Frequent off-line replanning adjuvantly enhances adaptation quality.","0":"Copyright \u00a9 2013. Published by Elsevier Ireland Ltd."},"affiliation":"","authors":["Oh S","Stewart J","Moseley J","Kelly V","Lim K","Xie J","Fyles A","Brock KK","Lundin A","Rehbinder H","Milosevic M","Jaffray D","Cho YB"],"article_id":"S0167-8140(13)00589-6,10.1016\/j.radonc.2013.11.006,24331862","keywords":[],"pub_status":"aheadofprint"}]';
        $articles = Json::decode($jsonString, Json::TYPE_ARRAY);
        $c = new Vancouver();
        $h = new ClassMethods();
        foreach($articles as $article) {
            $a = new Article();
            $a->setCitationGenerator($c);
            $h->hydrate($article, $a);
            $this->articles[] = $a;
        }
        $this->collection = new Collection(3, 1);
        $this->collection->setArticles($this->articles);
        $this->collection->setName('Thesis');
    }

    public function testInitialization()
    {
        $collection = new Collection(3, 1);
        $this->assertEquals(3, $collection->getId());
        $this->assertEquals(1, $collection->getUserId());

        $collection->setArticles($this->articles);
        $collection->setName('Thesis');
        $this->assertEquals('Thesis', $collection->getName());
        $i = 0;
        foreach($collection as $id => $article) {
            $this->assertEquals($this->articles[$i]->getId(), $id);
            $this->assertEquals($this->articles[$i]->getAbstract(), $article->getAbstract());
            $this->assertEquals($this->articles[$i]->getAuthors(), $article->getAuthors());
            $i++;
        }
    }

    public function testInitWithHydrator()
    {
        $h = new ClassMethods();
        $data['id'] = 7;
        $data['user_id'] = 2;
        $data['name'] = 'kirthika Thesis';
        $data['articles'] = $this->articles;
        $data['created_at'] = '2014-01-27 21:32:13';
        $data['updated_at'] = null;

        $id = $data['id'];
        $userId = $data['user_id'];

        unset($data['id']);
        unset($data['user_id']);

        /** @var Collection $collection */
        $collection = new Collection($id, $userId);

        $collection = $h->hydrate($data, $collection);

        $this->assertEquals($id, $collection->getId());
        $this->assertEquals($userId, $collection->getUserId());
        $this->assertEquals('kirthika Thesis', $collection->getName());
        $this->assertEquals((new \DateTime('2014-01-27 21:32:13'))->getTimestamp(),
                            $collection->getCreatedAt()->getTimestamp());
        $this->assertEquals(null, $collection->getUpdatedAt());
        $i = 0;
        foreach($collection as $id => $article) {
            $this->assertEquals($this->articles[$i]->getId(), $id);
            $this->assertEquals($this->articles[$i]->getAbstract(), $article->getAbstract());
            $this->assertEquals($this->articles[$i]->getAuthors(), $article->getAuthors());
            $i++;
        }
    }

    public function testArrayAccess()
    {
        $collection = $this->collection;
        unset($collection['24412268']);
        $this->assertEquals(null, $collection['24412268']);

        $i = 1;
        foreach($collection as $id => $article) {
            $this->assertEquals($this->articles[$i]->getId(), $id);
            $this->assertEquals($this->articles[$i]->getAbstract(), $article->getAbstract());
            $this->assertEquals($this->articles[$i]->getAuthors(), $article->getAuthors());
            $i++;
        }

        $collection['24412268'] = $this->articles[0];
        $this->articles[] = $this->articles[0];

        $i = 1;
        foreach($collection as $id => $article) {
            $this->assertEquals($this->articles[$i]->getId(), $id);
            $this->assertEquals($this->articles[$i]->getAbstract(), $article->getAbstract());
            $this->assertEquals($this->articles[$i]->getAuthors(), $article->getAuthors());
            $i++;
        }
    }
} 