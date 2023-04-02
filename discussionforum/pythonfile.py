import pandas as pd
import numpy as np


# import matplotlib.pyplot as plt
# import matplotlib.lines as mlines
# import seaborn as sns

import tensorflow as tf

import warnings

import pickle
import time

import re
from bs4 import BeautifulSoup
import nltk
from nltk.tokenize import ToktokTokenizer
from nltk.stem.wordnet import WordNetLemmatizer
from nltk.corpus import stopwords
from string import punctuation

from sklearn.feature_extraction.text import TfidfVectorizer

from sklearn.preprocessing import MultiLabelBinarizer
from sklearn.model_selection import train_test_split
from sklearn.model_selection import learning_curve
from sklearn.model_selection import ShuffleSplit
from sklearn.dummy import DummyClassifier
from sklearn.naive_bayes import MultinomialNB
from sklearn.linear_model import SGDClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.multiclass import OneVsRestClassifier
from sklearn.svm import LinearSVC
from sklearn.linear_model import Perceptron

from sklearn.neural_network import MLPClassifier
from sklearn.ensemble import RandomForestClassifier
from sklearn import model_selection
from sklearn.metrics import make_scorer
from sklearn.metrics import confusion_matrix
from sklearn.metrics import hamming_loss
from sklearn.cluster import KMeans
from sklearn.metrics import precision_score, recall_score


import logging

from scipy.sparse import hstack

warnings.filterwarnings("ignore")
import sys

    

vectorizer_X1=pickle.load(open('input_title.pkl', 'rb'))
vectorizer_X2=pickle.load(open('input_body.pkl', 'rb'))
multilabel_binarizer=pickle.load(open('output_tag.pkl', 'rb'))
input1=vectorizer_X1.transform([sys.argv[1]])
input2=vectorizer_X2.transform([sys.argv[2]])
model=pickle.load(open('model.pkl3', 'rb'))
output=model.predict(hstack([input1,input2]))

tags=multilabel_binarizer.inverse_transform(output)
tags=tags[0]
print(*tags)
